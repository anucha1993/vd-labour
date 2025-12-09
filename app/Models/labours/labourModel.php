<?php

namespace App\Models\labours;

use Carbon\Carbon;
use App\Models\staff\staffModel;
use Illuminate\Support\Facades\DB;
use App\Models\staff\staffSubModel;
use App\Models\country\countryModel;
use App\Models\files\labourFileModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\customers\customerModel;
use App\Models\positions\positionModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\locationTest\locationTestModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class labourModel extends Model
{
    use HasFactory;
    protected $table = 'labours';
    protected $primaryKey="labour_id";
    protected $fillable = [
       'labour_prefix',
       'labour_firstname',
       'labour_lastname',
       'labour_phone',
       'labour_passport_number',
       'labour_passport_issue',
       'labour_passport_expiry',
       'labour_country',
       'labour_job_group',
       'labour_position',
       'labour_location_test',
       'labour_staff',
       'labour_status',
       'labour_note',
       'labour_folder_year',
       'labour_examination',
       'labour_location_doc',
       'labour_register_number',
       'labour_file_count',
       'labour_file_list',
       'labour_path',
       'labour_customer',
       'labour_disease_expriry',
       'labour_disease_start',
       'labour_cid_start',
       'labour_cid_expriry',
       'labour_staff_sub',
       'created_by',
       'updated_by',
       'labour_disease_results_date',
       'labour_birthday',
       'labour_cid_deposit_date',
       'labour_cid_deposit_total',
       'labour_cidp_date',
       'labour_cidp_total',
       'labour_cidp_in_date',
       'labour_cidp_in_total',
       'labour_cid_deposit_status',
       'labour_refund_deposit_date',
       'labour_refund_deposit_total',
       'payment_type',
       'labour_cid_results',
       'labour_cid_results_file',
       'labour_cid_stand_date',
       'labour_disease_status',
       'labour_affidavit_start',
       'labour_affidavit_expriry',
       'labour_visa_submit_date',
       'labour_visa_approved_date',
       'labour_visa_status',
       'labour_visa_note',
       'labour_visa_reject_date',
       'labour_visa_start_date',
       'labour_visa_file',
       'lead_id',
    ];

    
    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'labour_customer', 'customer_id');
    }
    public function cid()
    {
        return $this->belongsTo(CIDresultsModel::class, 'labour_cid_results', 'cid_results_id');
    }
    public function country()
    {
        return $this->belongsTo(countryModel::class, 'labour_country', 'country_id');
    }
    public function jobGroup()
    {
        return $this->belongsTo(jobGroupModel::class, 'labour_job_group', 'job_group_id');
    }
    public function position()
    {
        return $this->belongsTo(positionModel::class, 'labour_position', 'position_id');
    }
    public function locationTest()
    {
        return $this->belongsTo(locationTestModel::class, 'labour_location_test', 'location_test_id');
    }
    public function staff()
    {
        return $this->belongsTo(staffModel::class, 'labour_staff', 'staff_id');
    }
    public function staffSub()
    {
        return $this->belongsTo(staffSubModel::class, 'labour_staff_sub', 'staff_sub_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function labourFile()
    {
        return $this->hasMany(labourFileModel::class, 'labour_id', 'labour_id');
    }

    public function leadModel()
    {
        return $this->belongsTo(\App\Models\leads\LeadModel::class, 'lead_id', 'lead_id');
    }


    public function scopeExpiringCidMoney($query)
   {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_status', 'wait')
                     ->whereNotNull('labour_cid_stand_date')
                     ->whereNull('labour_cid_deposit_date')
                     ->where(DB::raw('COALESCE(labour_cid_deposit_total, 0)'), '<=', 30000)
                     ->where('labour_cid_stand_date', '<=', $expiryDate);
    }

    public function scopeExpiringAffidavit($query)
    {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_status', 'wait')
                     ->whereNotNull('labour_affidavit_expriry')
                     ->where('labour_affidavit_expriry', '<=', $expiryDate);
    }


    // แจ้งเตือนผลโรค ก่อสร้าง ภายใน 15 วัน (รวมหมดอายุ)
    public function scopeExpiringDiseaseConstruct($query)
    {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_job_group', 1)
                     ->where('labour_status', 'wait')
                     ->whereNotNull('labour_disease_expriry')
                     ->where('labour_disease_expriry', '<=', $expiryDate);
    }

    // แจ้งเตือนผลโรค โรงงาน ภายใน 10 วัน (รวมหมดอายุ)
    public function scopeExpiringDiseaseFactory($query)
    {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_job_group', 2)
                     ->where('labour_status', 'wait')
                     ->whereNotNull('labour_disease_expriry')
                     ->where('labour_disease_expriry', '<=', $expiryDate);
    }

    // แจ้งเตือน CID ก่อสร้าง ภายใน 15 วัน (รวมหมดอายุ)
    public function scopeExpiringCIDConstruct($query)
    {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_job_group', 1)
                     ->where('labour_status', 'wait')
                     ->whereNotNull('labour_cid_expriry')
                     ->where('labour_cid_expriry', '<=', $expiryDate);
    }

    // แจ้งเตือน CID โรงงาน ภายใน 15 วัน (รวมหมดอายุ)
    public function scopeExpiringCIDFactory($query)
    {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_job_group', 2)
                     ->where('labour_status', 'wait')
                     ->whereNotNull('labour_cid_expriry')
                     ->where('labour_cid_expriry', '<=', $expiryDate);
    }

    // แจ้งเตือนพาสปอร์ต ภายใน 15 วัน (รวมหมดอายุ)
    public function scopeExpiringPassport($query)
    {
        $expiryDate = Carbon::now()->copy()->addDays(15)->toDateString();
        return $query->where('labour_status', 'wait')
                     ->whereNotNull('labour_passport_expiry')
                     ->where('labour_passport_expiry', '<=', $expiryDate);
    }

     public function scopeCountCancel($query)
    {
        return $query->where('labour_status', 'cancel');
    }

    public function scopeCountAll($query)
    {
        return $query; // ไม่ต้องทำอะไรเพิ่มเติม คืนค่า Query Builder ทั้งหมด
    }

    public function scopeCountSuccess($query)
    {
        return $query->where('labour_status', 'success');
    }

    // แจ้งเตือน VISA ไม่ Update (วันที่ยืนวีซ่าเกิน 75 วัน และ status = none)
    public function scopeVisaNotUpdate($query)
    {
        $checkDate = Carbon::now()->copy()->subDays(75)->toDateString();
        return $query->where('labour_status', 'wait')
                     ->whereNotNull('labour_visa_submit_date')
                     ->where('labour_visa_submit_date', '<=', $checkDate)
                     ->where(function($q) {
                         $q->whereNull('labour_visa_status')
                           ->orWhere('labour_visa_status', 'none');
                     });
    }

    // แจ้งเตือน VISA อนุมัติแล้ว (วันที่ยืนวีซ่าเกิน 75 วัน และ status = approved)
    public function scopeVisaApproved($query)
    {
        $checkDate = Carbon::now()->copy()->subDays(75)->toDateString();
        return $query->where('labour_status', 'wait')
                     ->whereNotNull('labour_visa_submit_date')
                     ->where('labour_visa_submit_date', '<=', $checkDate)
                     ->where('labour_visa_status', 'approved');
    }

    // แจ้งเตือน VISA ไม่อนุมัติ (วันที่ยืนวีซ่าเกิน 75 วัน และ status = rejected)
    public function scopeVisaRejected($query)
    {
        $checkDate = Carbon::now()->copy()->subDays(75)->toDateString();
        return $query->where('labour_status', 'wait')
                     ->whereNotNull('labour_visa_submit_date')
                     ->where('labour_visa_submit_date', '<=', $checkDate)
                     ->where('labour_visa_status', 'rejected');
    }

}
