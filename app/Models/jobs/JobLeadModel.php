<?php

namespace App\Models\jobs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\country\countryModel;
use Carbon\Carbon;

class JobLeadModel extends Model
{
    use HasFactory;
    
    protected $table = 'job_leads';
    protected $primaryKey = 'job_lead_id';
    
    protected $fillable = [
        'job_lead_number',
        'job_id',
        'lead_id',
        'job_lead_status',
        'remarks',
        'is_locked',
        'locked_at',
        'unlocked_at',
        'created_by',
        'updated_by'
    ];
    
    protected $casts = [
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
        'is_locked' => 'boolean',
    ];
    
    // สถานะที่ต้องล็อคคนงาน
    const LOCKED_STATUSES = [
        'ส่งแล้ว',
        'กำลังพิจารณา', 
        'นัดสัมภาษณ์',
        'เสนองาน',
        'ตอบรับ'
    ];
    
    // สถานะที่ปลดล็อคคนงาน
    const UNLOCKED_STATUSES = [
        'ร่าง',
        'ปฏิเสธ',
        'ถอน'
    ];
    
    // Relationships
    public function job()
    {
        return $this->belongsTo(JobModel::class, 'job_id', 'job_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function lead()
    {
        return $this->belongsTo(\App\Models\leads\LeadModel::class, 'lead_id', 'lead_id');
    }
    
    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('job_lead_status', $status);
    }
    
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }
    
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }
    
    public function scopeByJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }
    
    // Accessors & Mutators
    public function getStatusBadgeColorAttribute()
    {
        $colors = [
            'ร่าง' => 'secondary',
            'ส่งแล้ว' => 'primary',
            'กำลังพิจารณา' => 'info',
            'นัดสัมภาษณ์' => 'warning',
            'เสนองาน' => 'warning',
            'ตอบรับ' => 'success',
            'ปฏิเสธ' => 'danger',
            'ถอน' => 'dark'
        ];
        
        return $colors[$this->job_lead_status] ?? 'secondary';
    }
    
    public function getIsLockableStatusAttribute()
    {
        return in_array($this->job_lead_status, self::LOCKED_STATUSES);
    }
    
    public function getIsUnlockableStatusAttribute()
    {
        return in_array($this->job_lead_status, self::UNLOCKED_STATUSES);
    }
    
    // Static methods
    public static function generateJobLeadNumber($countryId)
    {
        $country = countryModel::find($countryId);
        $countryCode = $country ? $country->country_code : 'XX';
        $year = date('Y');
        $prefix = "{$countryCode}{$year}-";
        
        $lastLead = static::whereHas('job', function($query) use ($countryId) {
                                $query->where('country_id', $countryId);
                            })
                            ->where('job_lead_number', 'like', $prefix . '%')
                            ->orderBy('job_lead_number', 'desc')
                            ->first();
        
        if ($lastLead) {
            $lastNumber = intval(substr($lastLead->job_lead_number, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
    
    // Methods for locking/unlocking
    public function lock()
    {
        if ($this->is_lockable_status) {
            $this->update([
                'is_locked' => true,
                'locked_at' => now()
            ]);
        }
    }
    
    public function unlock($reason = null)
    {
        $this->update([
            'is_locked' => false,
            'unlocked_at' => now(),
            'remarks' => $reason ? $this->remarks . "\n[ปลดล็อค: {$reason}]" : $this->remarks
        ]);
    }
    
    public function forceUnlock($reason = 'Admin บังคับปลดล็อค')
    {
        $this->update([
            'is_locked' => false,
            'unlocked_at' => now(),
            'remarks' => $this->remarks . "\n[บังคับปลดล็อค: {$reason}]"
        ]);
    }
    
    // Check if lead is available for new applications
    public static function isLeadAvailable($leadId)
    {
        return !static::where('lead_id', $leadId)
                     ->where('is_locked', true)
                     ->exists();
    }
    
    // Event handlers
    protected static function booted()
    {
        static::creating(function ($jobLead) {
            // Generate job lead number based on job's country
            if (empty($jobLead->job_lead_number) && $jobLead->job_id) {
                $job = JobModel::find($jobLead->job_id);
                if ($job) {
                    $jobLead->job_lead_number = static::generateJobLeadNumber($job->country_id);
                }
            }
            
            if (empty($jobLead->created_by)) {
                $jobLead->created_by = auth()->id() ?? 1;
            }
            if (empty($jobLead->updated_by)) {
                $jobLead->updated_by = auth()->id() ?? 1;
            }
        });
        
        static::updating(function ($jobLead) {
            if (empty($jobLead->updated_by)) {
                $jobLead->updated_by = auth()->id() ?? 1;
            }
            
            // Auto lock/unlock based on status change
            $originalStatus = $jobLead->getOriginal('job_lead_status');
            $newStatus = $jobLead->job_lead_status;
            
            if ($originalStatus !== $newStatus) {
                if (in_array($newStatus, self::LOCKED_STATUSES)) {
                    $jobLead->is_locked = true;
                    $jobLead->locked_at = now();
                } elseif (in_array($newStatus, self::UNLOCKED_STATUSES)) {
                    $jobLead->is_locked = false;
                    $jobLead->unlocked_at = now();
                }
            }
        });
    }
}