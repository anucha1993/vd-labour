<?php

namespace App\Models\jobs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class JobLeadActivityModel extends Model
{
    use HasFactory;
    
    protected $table = 'job_lead_activities';
    protected $primaryKey = 'activity_id';
    
    protected $fillable = [
        'job_lead_id',
        'lead_id',
        'job_lead_number',
        'activity_type',
        'old_status',
        'new_status',
        'reason',
        'remarks',
        'changes',
        'user_id'
    ];
    
    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationships
    public function jobLead()
    {
        return $this->belongsTo(JobLeadModel::class, 'job_lead_id', 'job_lead_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // Helper method to get activity icon
    public function getActivityIcon()
    {
        $icons = [
            'created' => 'bi-plus-circle',
            'status_changed' => 'bi-arrow-left-right',
            'updated' => 'bi-pencil',
            'deleted' => 'bi-trash',
            'bulk_updated' => 'bi-lightning',
        ];
        
        return $icons[$this->activity_type] ?? 'bi-circle';
    }
    
    // Helper method to get activity color
    public function getActivityColor()
    {
        $colors = [
            'created' => 'success',
            'status_changed' => 'primary',
            'updated' => 'warning',
            'deleted' => 'danger',
            'bulk_updated' => 'info',
        ];
        
        return $colors[$this->activity_type] ?? 'secondary';
    }
    
    // Helper method to get activity description
    public function getActivityDescription()
    {
        switch ($this->activity_type) {
            case 'created':
                return 'สร้างใบสมัคร';
            case 'status_changed':
                return "เปลี่ยนสถานะจาก \"{$this->old_status}\" เป็น \"{$this->new_status}\"";
            case 'updated':
                return 'แก้ไขข้อมูลใบสมัคร';
            case 'deleted':
                return 'ยกเลิกใบสมัคร';
            case 'bulk_updated':
                return 'อัปเดตแบบกลุ่ม';
            default:
                return 'กิจกรรม';
        }
    }
}
