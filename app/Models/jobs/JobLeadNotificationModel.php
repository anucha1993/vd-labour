<?php

namespace App\Models\jobs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class JobLeadNotificationModel extends Model
{
    use HasFactory;
    
    protected $table = 'job_lead_notifications';
    protected $primaryKey = 'notification_id';
    
    protected $fillable = [
        'job_lead_id',
        'notification_type',
        'current_status',
        'sent_at',
        'responded_at',
        'response',
        'responded_by',
        'is_read',
        'note'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
        'is_read' => 'boolean',
    ];
    
    // Notification types
    const TYPE_7_DAYS = '7_days';
    const TYPE_14_DAYS = '14_days';
    const TYPE_21_DAYS = '21_days';
    const TYPE_OVER_21_DAYS = 'over_21_days';
    
    // Response types
    const RESPONSE_WAIT = 'wait';
    const RESPONSE_WITHDRAW = 'withdraw';
    
    // Relationships
    public function jobLead()
    {
        return $this->belongsTo(JobLeadModel::class, 'job_lead_id', 'job_lead_id');
    }
    
    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
    
    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    public function scopePending($query)
    {
        return $query->whereNull('responded_at');
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }
    
    // Helper methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
    
    public function respond($response, $userId, $note = null)
    {
        $this->update([
            'responded_at' => now(),
            'response' => $response,
            'responded_by' => $userId,
            'note' => $note,
            'is_read' => true
        ]);
    }
    
    public function getDaysLabel()
    {
        return match($this->notification_type) {
            self::TYPE_7_DAYS => '7 วัน',
            self::TYPE_14_DAYS => '14 วัน',
            self::TYPE_21_DAYS => '21 วัน',
            self::TYPE_OVER_21_DAYS => 'มากกว่า 21 วัน',
            default => 'ไม่ทราบ'
        };
    }
    
    public function canWait()
    {
        return $this->notification_type !== self::TYPE_OVER_21_DAYS;
    }
}
