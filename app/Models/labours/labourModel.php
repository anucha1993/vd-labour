<?php

namespace App\Models\labours;

use Carbon\Carbon;
use App\Models\files\labourFileModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
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
    ];

    
    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'labour_customer', 'customer_id');
    }
    public function labourFile()
    {
        return $this->hasMany(labourFileModel::class, 'labour_id', 'labour_id');
    }


    //แจ้งเตือนผลโรค ก่อสร้าง ก่อนหมดอายุ 15 วัน
    public function scopeExpiringDiseaseConstruct($query)
    {
        $today = Carbon::now();
        $expiryDate = $today->addDays(15)->toDateString();
        return $query->where('labour_job_group', 1)->where('labour_status', 'wait')
                     ->whereNotNull('labour_disease_expriry')
                     ->where('labour_disease_expriry', '<=', $expiryDate) // แก้ไขเป็น <= เพื่อแจ้งเตือนก่อนหมดอายุ
                     ->where('labour_disease_expriry', '>=', $today->toDateString());
    }
      //แจ้งเตือนผลโรค โรงงาน ก่อนหมดอายุ 10 วัน
    public function scopeExpiringDiseaseFactory($query)
    {
        $today = Carbon::now();
        $expiryDate = $today->addDays(10)->toDateString();
        return $query->where('labour_job_group', 2)->where('labour_status', 'wait')
                     ->whereNotNull('labour_disease_expriry')
                     ->where('labour_disease_expriry', '<=', $expiryDate) // แก้ไขเป็น <= เพื่อแจ้งเตือนก่อนหมดอายุ
                     ->where('labour_disease_expriry', '>=', $today->toDateString());
    }
  //แจ้งเตือน CID ก่อสร้าง ก่อนหมดอายุ 15 วัน
    public function scopeExpiringCIDConstruct($query)
    {
        $today = Carbon::now();
        $expiryDate = $today->addDays(15)->toDateString();
        return $query->where('labour_job_group', 1)->where('labour_status', 'wait')
                     ->whereNotNull('labour_cid_expriry')
                     ->where('labour_cid_expriry', '<=', $expiryDate) // แก้ไขเป็น <= เพื่อแจ้งเตือนก่อนหมดอายุ
                     ->where('labour_cid_expriry', '>=', $today->toDateString());
    }
    //แจ้งเตือน CID ก่อสร้าง ก่อนหมดอายุ 15 วัน
    public function scopeExpiringCIDFactory($query)
    {
        $today = Carbon::now();
        $expiryDate = $today->addDays(15)->toDateString();
        return $query->where('labour_job_group', 2)->where('labour_status', 'wait')
                     ->whereNotNull('labour_cid_expriry')
                     ->where('labour_cid_expriry', '<=', $expiryDate) // แก้ไขเป็น <= เพื่อแจ้งเตือนก่อนหมดอายุ
                     ->where('labour_cid_expriry', '>=', $today->toDateString());
    }

     //แจ้งเตือน Passport ก่อนหมดอายุ 15 วัน
     public function scopeExpiringPassport($query)
     {
         $today = Carbon::now();
         $expiryDate = $today->addDays(15)->toDateString();
         return $query->where('labour_status', 'wait')
                      ->whereNotNull('labour_passport_expiry')
                      ->where('labour_passport_expiry', '<=', $expiryDate) // แก้ไขเป็น <= เพื่อแจ้งเตือนก่อนหมดอายุ
                      ->where('labour_passport_expiry', '>=', $today->toDateString());
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



    

}
