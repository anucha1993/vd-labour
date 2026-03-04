<?php

namespace App\Models\jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\demands\DemandModel;
use App\Models\country\countryModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\customers\customerModel;
use App\Models\positions\positionModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobModel extends Model
{
    use HasFactory;
    
    protected $table = 'jobs';
    protected $primaryKey = 'job_id';
    
    protected $fillable = [
        'job_number',
        'job_name',
        'country_id',
        'dm_id',
        'job_group_id',
        'position_id',
        'position_ids',
        'job_total',
        'job_start_date',
        'job_end_date',
        'job_status',
        'customer_id',
        'created_by',
        'updated_by'
    ];
    
    protected $casts = [
        'job_start_date' => 'date',
        'job_end_date' => 'date',
        'position_ids' => 'array',
    ];
    
    // Relationships
    public function country()
    {
        return $this->belongsTo(countryModel::class, 'country_id', 'country_id');
    }
     public function customer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }
    
    public function demand()
    {
        return $this->belongsTo(DemandModel::class, 'dm_id', 'dm_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function jobLeads()
    {
        return $this->hasMany(JobLeadModel::class, 'job_id', 'job_id');
    }
      public function jobgroup()
    {
        return $this->belongsTo(jobGroupModel::class, 'job_group_id', 'job_group_id');
    }
     public function position()
    {
        return $this->belongsTo(positionModel::class, 'position_id', 'position_id');
    }

    /**
     * Get all positions (from position_ids JSON)
     */
    public function getPositionsAttribute()
    {
        if (!$this->position_ids || !is_array($this->position_ids)) {
            // Fallback to single position_id
            return $this->position ? collect([$this->position]) : collect();
        }
        return positionModel::whereIn('position_id', $this->position_ids)->get();
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('job_status', 'เปิดรับสมัคร');
    }
    
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
    
    // Accessors
    public function getIsExpiredAttribute()
    {
        if (!$this->job_end_date) {
            return false; // ไม่มีวันสิ้นสุด = ไม่หมดอายุ
        }
        
        return Carbon::now()->isAfter($this->job_end_date);
    }
    
    public function getApplicationCountAttribute()
    {
        return $this->jobLeads()->count();
    }
    
    public function getAcceptedCountAttribute()
    {
        return $this->jobLeads()->where('job_lead_status', 'ตอบรับ')->count();
    }
    
    public function getRemainingPositionsAttribute()
    {
        return max(0, $this->job_total - $this->accepted_count);
    }
    
    // Static methods for running number
    public static function generateJobNumber()
    {
        $year = date('Y');
        $prefix = "JOB{$year}-";
        
        $lastJob = static::where('job_number', 'like', $prefix . '%')
                         ->orderBy('job_number', 'desc')
                         ->first();
        
        if ($lastJob) {
            $lastNumber = intval(substr($lastJob->job_number, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
    
    // Event handlers
    protected static function booted()
    {
        static::creating(function ($job) {
            if (empty($job->job_number)) {
                $job->job_number = static::generateJobNumber();
            }
            
            if (empty($job->created_by)) {
                $job->created_by = auth()->id() ?? 1; // Default to user ID 1 if not authenticated
            }
            if (empty($job->updated_by)) {
                $job->updated_by = auth()->id() ?? 1;
            }
        });
        
        static::updating(function ($job) {
            if (empty($job->updated_by)) {
                $job->updated_by = auth()->id() ?? 1;
            }
        });
    }
}