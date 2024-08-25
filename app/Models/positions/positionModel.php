<?php

namespace App\Models\positions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class positionModel extends Model
{
    use HasFactory;
    protected $table = 'position';
    protected $primaryKey="position_id";
    protected $fillable = [
        'position_name',
        'position_status',
        'job_group_id',
    ];
}
