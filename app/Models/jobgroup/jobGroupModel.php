<?php

namespace App\Models\jobgroup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobGroupModel extends Model
{
    use HasFactory;
    protected $table = 'job_group';
    protected $primaryKey="job_group_id";
    protected $fillable = [
        'job_group_name',
        'job_group_status',
    ];
}
