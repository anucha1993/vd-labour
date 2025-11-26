<?php

namespace App\Models\positions;

use App\Models\jobgroup\jobGroupModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class positionModel extends Model
{
    use HasFactory;
    protected $table = 'position';
    protected $primaryKey="position_id";
    protected $fillable = [
        'position_name',
        'position_name_th',
        'position_status',
        'job_group_id',
    ];

    public function jobGroup()
    {
        return $this->belongsTo(jobGroupModel::class, 'job_group_id', 'job_group_id');
    }
}
