<?php

namespace App\Traits;

use App\Models\jobs\JobLeadActivityModel;

trait LogsJobLeadActivity
{
    /**
     * บันทึก Activity Log
     * 
     * @param string $activityType ประเภทกิจกรรม (created, status_changed, updated, deleted, bulk_updated)
     * @param string|null $oldStatus สถานะเก่า (สำหรับ status_changed)
     * @param string|null $newStatus สถานะใหม่ (สำหรับ status_changed)
     * @param string|null $reason เหตุผล (บังคับสำหรับ ปฏิเสธ และ ถอน)
     * @param string|null $remarks หมายเหตุเพิ่มเติม
     * @param array $changes ข้อมูลการเปลี่ยนแปลงอื่นๆ
     * @return JobLeadActivityModel
     */
    public function logActivity(
        string $activityType,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        ?string $reason = null,
        ?string $remarks = null,
        array $changes = []
    ): JobLeadActivityModel {
        return JobLeadActivityModel::create([
            'job_lead_id' => $this->job_lead_id,
            'lead_id' => $this->lead_id, // เก็บ lead_id ไว้
            'job_lead_number' => $this->job_lead_number, // เก็บเลขที่ใบสมัครไว้
            'activity_type' => $activityType,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'reason' => $reason,
            'remarks' => $remarks,
            'changes' => !empty($changes) ? $changes : null,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * บันทึกการสร้างใบสมัคร
     */
    public function logCreation(?string $remarks = null): JobLeadActivityModel
    {
        return $this->logActivity(
            activityType: 'created',
            newStatus: $this->job_lead_status,
            remarks: $remarks
        );
    }

    /**
     * บันทึกการเปลี่ยนสถานะ
     */
    public function logStatusChange(
        string $oldStatus,
        string $newStatus,
        ?string $reason = null,
        ?string $remarks = null
    ): JobLeadActivityModel {
        return $this->logActivity(
            activityType: 'status_changed',
            oldStatus: $oldStatus,
            newStatus: $newStatus,
            reason: $reason,
            remarks: $remarks
        );
    }

    /**
     * บันทึกการอัปเดตข้อมูล
     */
    public function logUpdate(
        array $changes = [],
        ?string $remarks = null
    ): JobLeadActivityModel {
        return $this->logActivity(
            activityType: 'updated',
            changes: $changes,
            remarks: $remarks
        );
    }

    /**
     * บันทึกการลบ
     */
    public function logDeletion(?string $reason = null): JobLeadActivityModel
    {
        return $this->logActivity(
            activityType: 'deleted',
            oldStatus: $this->job_lead_status,
            reason: $reason
        );
    }

    /**
     * บันทึกการอัปเดตแบบหลายรายการ (Bulk Update)
     */
    public function logBulkUpdate(
        string $oldStatus,
        string $newStatus,
        ?string $reason = null,
        ?string $remarks = null
    ): JobLeadActivityModel {
        return $this->logActivity(
            activityType: 'bulk_updated',
            oldStatus: $oldStatus,
            newStatus: $newStatus,
            reason: $reason,
            remarks: $remarks
        );
    }
}
