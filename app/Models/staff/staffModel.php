<?php

namespace App\Models\staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staffModel extends Model
{
    use HasFactory;
    protected $table = 'staff';
    protected $primaryKey="staff_id";
    protected $fillable = [
        'staff_name',
        'staff_nickname',
        'staff_status',
    ];
}
