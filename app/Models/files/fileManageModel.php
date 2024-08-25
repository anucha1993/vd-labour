<?php

namespace App\Models\files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fileManageModel extends Model
{
    use HasFactory;
    protected $table = 'file_manage';
    protected $primaryKey="file_manage_id";
    protected $fillable = [
        'file_manage_name',
        'file_manage_status',
    ];
}
