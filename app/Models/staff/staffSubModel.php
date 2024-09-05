<?php

namespace App\Models\staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staffSubModel extends Model
{
    use HasFactory;
    protected $table = 'staff_sub';
    protected $primaryKey="staff_sub_id";
    protected $fillable = [
        'staff_sub_name',
        'staff_sub_phone',
        'staff_status',
        'staff_sub_staff',
    ];
}
