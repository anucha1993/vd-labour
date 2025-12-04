<?php

namespace App\Observers;

use App\Models\jobs\JobLeadModel;
use App\Services\JobLeadNotificationService;

class JobLeadObserver
{
    protected $notificationService;
    
    public function __construct(JobLeadNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    /**
     * Handle the JobLeadModel "created" event.
     */
    public function created(JobLeadModel $jobLead): void
    {
        // เมื่อสร้างใบสมัครใหม่ ตรวจสอบว่าเป็นสถานะที่ต้องติดตาม
        if ($this->shouldTrackStatus($jobLead->job_lead_status)) {
            $this->notificationService->startTracking($jobLead);
        }
    }

    /**
     * Handle the JobLeadModel "updated" event.
     */
    public function updated(JobLeadModel $jobLead): void
    {
        // ตรวจสอบว่ามีการเปลี่ยนสถานะหรือไม่
        if ($jobLead->isDirty('job_lead_status')) {
            $oldStatus = $jobLead->getOriginal('job_lead_status');
            $newStatus = $jobLead->job_lead_status;
            
            // ถ้าเปลี่ยนจากสถานะที่ไม่ต้องติดตาม ไปเป็นสถานะที่ต้องติดตาม
            if (!$this->shouldTrackStatus($oldStatus) && $this->shouldTrackStatus($newStatus)) {
                $this->notificationService->startTracking($jobLead);
            }
            
            // ถ้าเปลี่ยนจากสถานะที่ติดตาม ไปเป็นสถานะที่ไม่ต้องติดตาม
            if ($this->shouldTrackStatus($oldStatus) && !$this->shouldTrackStatus($newStatus)) {
                $this->notificationService->stopTracking($jobLead);
            }
        }
    }

    /**
     * Handle the JobLeadModel "deleted" event.
     */
    public function deleted(JobLeadModel $jobLead): void
    {
        // หยุดติดตามเมื่อลบใบสมัคร
        $this->notificationService->stopTracking($jobLead);
    }

    /**
     * ตรวจสอบว่าสถานะนี้ต้องติดตามหรือไม่
     */
    protected function shouldTrackStatus($status): bool
    {
        return in_array($status, ['ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน']);
    }
}
