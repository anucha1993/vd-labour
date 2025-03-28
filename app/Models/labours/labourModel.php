<?php

namespace App\Models\labours;

use App\Models\customers\customerModel;
use App\Models\files\labourFileModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];


    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'labour_customer', 'customer_id');
    }
    public function labourFile()
    {
        return $this->hasMany(labourFileModel::class, 'labour_id', 'labour_id');
    }

}
