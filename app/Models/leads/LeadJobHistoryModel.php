<?php

namespace App\Models\leads;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadJobHistoryModel extends Model
{
    use HasFactory;
    
    protected $table = 'lead_job_history';
    protected $primaryKey = 'job_history_id';
    
    protected $fillable = [
        'lead_id',
        'company_type',
        'company_name',
        'position',
        'country',
        'experience_years',
        'start_date',
        'end_date',
        'description',
        'display_order'
    ];
    
    public function lead()
    {
        return $this->belongsTo(LeadModel::class, 'lead_id', 'lead_id');
    }
}
