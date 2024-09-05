<?php

namespace App\Models\labours;

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
       'labour_cid_expriry',
       'labour_staff_sub',
       'created_by',
       'updated_by',
    ];
}
