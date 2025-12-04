<?php

namespace App\Services;

use App\Models\jobs\JobLeadModel;
use App\Models\jobs\JobLeadNotificationModel;
use Carbon\Carbon;

class JobLeadNotificationService
{
    /**
     * เริ่มติดตามใบสมัครงาน
     */
    public function startTracking(JobLeadModel $jobLead)
    {
        // ลบการแจ้งเตือนเก่าที่ยังไม่ได้ตอบสนอง (ถ้ามี)
        JobLeadNotificationModel::where('job_lead_id', $jobLead->job_lead_id)
            ->whereNull('responded_at')
            ->delete();
            
        \Log::info("Started tracking job lead #{$jobLead->job_lead_number} with status: {$jobLead->job_lead_status}");
    }
    
    /**
     * หยุดติดตามใบสมัครงาน
     */
    public function stopTracking(JobLeadModel $jobLead)
    {
        // ลบการแจ้งเตือนที่ยังไม่ได้ตอบสนอง
        JobLeadNotificationModel::where('job_lead_id', $jobLead->job_lead_id)
            ->whereNull('responded_at')
            ->delete();
            
        \Log::info("Stopped tracking job lead #{$jobLead->job_lead_number}");
    }
    
    /**
     * ตรวจสอบและสร้างการแจ้งเตือนสำหรับใบสมัครที่ครบกำหนด
     */
    public function checkAndCreateNotifications()
    {
        // สถานะที่ต้องติดตาม
        $trackingStatuses = ['ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน'];
        
        $jobLeads = JobLeadModel::whereIn('job_lead_status', $trackingStatuses)
            ->get();
            
        foreach ($jobLeads as $jobLead) {
            $this->processJobLead($jobLead);
        }
        
        \Log::info("Processed " . $jobLeads->count() . " job leads for notifications");
    }
    
    /**
     * ประมวลผลใบสมัครแต่ละรายการ
     */
    protected function processJobLead(JobLeadModel $jobLead)
    {
        $createdDate = Carbon::parse($jobLead->created_at);
        $today = Carbon::today();
        $daysPassed = $createdDate->diffInDays($today);
        
        // ตรวจสอบว่ามีการแจ้งเตือนที่รอการตอบสนองอยู่หรือไม่
        $pendingNotification = JobLeadNotificationModel::where('job_lead_id', $jobLead->job_lead_id)
            ->whereNull('responded_at')
            ->latest('sent_at')
            ->first();
            
        // ถ้ามีการแจ้งเตือนที่รอการตอบสนอง ไม่ต้องสร้างใหม่
        if ($pendingNotification) {
            return;
        }
        
        // หาการแจ้งเตือนล่าสุดที่ได้รับการตอบสนองแล้ว
        $lastRespondedNotification = JobLeadNotificationModel::where('job_lead_id', $jobLead->job_lead_id)
            ->whereNotNull('responded_at')
            ->latest('sent_at')
            ->first();
            
        // คำนวณวันที่ต้องแจ้งเตือนครั้งต่อไป
        $baseDate = $createdDate;
        if ($lastRespondedNotification && $lastRespondedNotification->response === JobLeadNotificationModel::RESPONSE_WAIT) {
            // ถ้ามีการตอบกลับว่ารอต่อ ให้นับจากวันที่ตอบกลับ
            $baseDate = Carbon::parse($lastRespondedNotification->responded_at);
        }
        
        $daysSinceBase = $baseDate->diffInDays($today);
        
        // กำหนดประเภทการแจ้งเตือน
        $notificationType = null;
        
        if ($daysSinceBase >= 21 && !$lastRespondedNotification) {
            // ครบ 21 วันแล้ว และยังไม่เคยตอบกลับ
            $notificationType = JobLeadNotificationModel::TYPE_21_DAYS;
        } elseif ($daysSinceBase >= 21 && $lastRespondedNotification) {
            // มากกว่า 21 วันแล้ว และเคยตอบกลับแล้ว
            if ($lastRespondedNotification->notification_type === JobLeadNotificationModel::TYPE_21_DAYS && 
                $lastRespondedNotification->response === JobLeadNotificationModel::RESPONSE_WAIT) {
                // เคยตอบที่ 21 วันว่ารอต่อ แล้วเกิน 21 วันอีกครั้ง -> ห้ามรอแล้ว
                $notificationType = JobLeadNotificationModel::TYPE_OVER_21_DAYS;
            }
        } elseif ($daysSinceBase >= 14 && !in_array($lastRespondedNotification?->notification_type, [JobLeadNotificationModel::TYPE_14_DAYS, JobLeadNotificationModel::TYPE_21_DAYS])) {
            $notificationType = JobLeadNotificationModel::TYPE_14_DAYS;
        } elseif ($daysSinceBase >= 7 && !$lastRespondedNotification) {
            $notificationType = JobLeadNotificationModel::TYPE_7_DAYS;
        }
        
        // สร้างการแจ้งเตือนถ้าจำเป็น
        if ($notificationType) {
            $this->createNotification($jobLead, $notificationType);
        }
    }
    
    /**
     * สร้างการแจ้งเตือน
     */
    protected function createNotification(JobLeadModel $jobLead, string $type)
    {
        JobLeadNotificationModel::create([
            'job_lead_id' => $jobLead->job_lead_id,
            'notification_type' => $type,
            'current_status' => $jobLead->job_lead_status,
            'sent_at' => now(),
            'is_read' => false
        ]);
        
        \Log::info("Created {$type} notification for job lead #{$jobLead->job_lead_number}");
    }
    
    /**
     * ตอบสนองการแจ้งเตือน
     */
    public function respondToNotification($notificationId, $response, $userId, $note = null)
    {
        $notification = JobLeadNotificationModel::findOrFail($notificationId);
        
        // ตรวจสอบว่าสามารถเลือก "รอต่อไป" ได้หรือไม่
        if ($response === JobLeadNotificationModel::RESPONSE_WAIT && !$notification->canWait()) {
            throw new \Exception('ไม่สามารถเลือก "รอต่อไป" ได้สำหรับการแจ้งเตือนประเภทนี้');
        }
        
        $notification->respond($response, $userId, $note);
        
        // ถ้าเลือกถอนใบสมัคร ให้อัปเดตสถานะ
        if ($response === JobLeadNotificationModel::RESPONSE_WITHDRAW) {
            $jobLead = $notification->jobLead;
            $jobLead->update(['job_lead_status' => 'ถอน']);
        }
        
        return $notification;
    }
    
    /**
     * ดึงการแจ้งเตือนที่ยังไม่ได้อ่าน
     */
    public function getUnreadNotifications($userId = null)
    {
        $query = JobLeadNotificationModel::with(['jobLead.lead', 'jobLead.job'])
            ->unread()
            ->pending()
            ->latest('sent_at');
            
        return $query->get();
    }
    
    /**
     * นับจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน
     */
    public function getUnreadCount($userId = null)
    {
        return JobLeadNotificationModel::unread()
            ->pending()
            ->count();
    }
}
