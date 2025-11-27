<?php

namespace App\Models\inducstry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inducstryTypeModel extends Model
{
     use HasFactory;
    protected $table = 'inducstry_type';
    protected $primaryKey="inducstry_type_id";
    protected $fillable = [
        'industry_type_name',
        'industry_type_name_th',
    ];
}
