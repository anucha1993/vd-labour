<?php

namespace App\Models\country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countryModel extends Model
{
    use HasFactory;
    protected $table = 'country';
    protected $primaryKey="country_id";
    protected $fillable = [
        'country_code', // เป็น Code ที่เอาไว้ดึงไปตั้งรหัสใน ใบสมัคร I=อิสราเอล,J=ญี่ปุ่น,P=โปรตุเกส,T=ไต้หวัน
        'country_name_th',
        'country_name_en',
        'country_status',
    ];
}
