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
        'staff_sub_status',
        'staff_sub_staff',
    ];

    public function staff()
    {
        return $this->belongsTo(staffModel::class, 'staff_sub_staff', 'staff_id');
    }
}
