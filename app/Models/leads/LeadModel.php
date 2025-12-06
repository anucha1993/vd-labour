<?php

namespace App\Models\leads;

use App\Models\country\countryModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\positions\positionModel;
use App\Models\staff\staffModel;
use App\Models\labours\labourModel;
use App\Models\examinations\examinationRoundModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadModel extends Model
{
    use HasFactory;
    
    protected $table = 'leads';
    protected $primaryKey = 'lead_id';
    
    protected $fillable = [
        'lead_prefix', 'lead_firstname', 'lead_lastname', 'lead_father_name', 
        'lead_mother_name', 'lead_gender',
        'lead_marital_status', 'lead_birthday', 'lead_age', 'lead_height',
        'lead_weight', 'lead_bmi', 'lead_phone', 'lead_phone_2', 'lead_email',
        'lead_address', 'lead_passport_number', 'lead_passport_issue_date',
        'lead_passport_expiry_date', 'lead_id_card_number', 'lead_shirt_size',
        'lead_pant_size', 'lead_shoes_size', 'lead_education',
        'lead_chinese_speaking', 'lead_english_speaking', 'lead_other_language',
        'lead_work_israel', 'lead_work_israel_details', 'lead_criminal_history',
        'lead_criminal_details', 'lead_eyesight', 'lead_color_blindness',
        'lead_additional_details', 'lead_emergency_name', 'lead_emergency_phone', 
        'lead_emergency_status', 'lead_bank_account_number', 'lead_bank_name',
        'lead_driving_license', 'lead_car_type', 
        'lead_license_valid_until', 'position_id', 'position_id_2', 'position_id_3', 
        'lead_skills', 'country_id', 'job_group_id', 'lead_status', 'lead_note',
        'staff_id', 'lead_photo', 'labour_id', 'converted_at',
        'examination_round_id', 'lead_date_location', 'lead_recommender_staff_sub_id',
        'documents', 'license_number', 'created_by', 'updated_by'
    ];
    
    protected $casts = [
        'lead_skills' => 'array',
        'documents' => 'array',
        'lead_birthday' => 'date',
        'lead_passport_issue_date' => 'date',
        'lead_passport_expiry_date' => 'date',
        'lead_license_valid_until' => 'date',
        'converted_at' => 'datetime',
    ];
    
    // Relationships
    public function position()
    {
        return $this->belongsTo(positionModel::class, 'position_id', 'position_id');
    }
    
    public function position2()
    {
        return $this->belongsTo(positionModel::class, 'position_id_2', 'position_id');
    }
    
    public function position3()
    {
        return $this->belongsTo(positionModel::class, 'position_id_3', 'position_id');
    }
    
    public function country()
    {
        return $this->belongsTo(countryModel::class, 'country_id', 'country_id');
    }
    
    public function jobGroup()
    {
        return $this->belongsTo(jobGroupModel::class, 'job_group_id', 'job_group_id');
    }
    
    public function staff()
    {
        return $this->belongsTo(staffModel::class, 'staff_id', 'staff_id');
    }
    
    public function jobHistory()
    {
        return $this->hasMany(LeadJobHistoryModel::class, 'lead_id', 'lead_id')->orderBy('display_order');
    }
    
    public function labour()
    {
        return $this->belongsTo(labourModel::class, 'labour_id', 'labour_id');
    }
    
    public function examinationRound()
    {
        return $this->belongsTo(examinationRoundModel::class, 'examination_round_id', 'examination_round_id');
    }
    
    public function recommenderStaff()
    {
        return $this->belongsTo(\App\Models\staff\staffSubModel::class, 'lead_recommender_staff_sub_id', 'staff_sub_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }
    
    public function jobLeads()
    {
        return $this->hasMany(\App\Models\jobs\JobLeadModel::class, 'lead_id', 'lead_id');
    }
    
    // Helper methods
    public function getFullNameAttribute()
    {
        return trim($this->lead_prefix . ' ' . $this->lead_firstname . ' ' . $this->lead_lastname);
    }
    
    public function isConverted()
    {
        return $this->lead_status === 'converted' && $this->labour_id !== null;
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'new' => '<span class="badge bg-primary">ใหม่</span>',
            'contacted' => '<span class="badge bg-info">ติดต่อแล้ว</span>',
            'interview' => '<span class="badge bg-warning">นัดสัมภาษณ์</span>',
            'qualified' => '<span class="badge bg-success">ผ่านคุณสมบัติ</span>',
            'converted' => '<span class="badge bg-dark">Convert แล้ว</span>',
            'rejected' => '<span class="badge bg-danger">ไม่ผ่าน</span>',
        ];
        
        return $badges[$this->lead_status] ?? '<span class="badge bg-secondary">-</span>';
    }
}
