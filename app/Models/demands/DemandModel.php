<?php

namespace App\Models\demands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\inducstry\inducstryTypeModel;
use App\Models\positions\positionModel;

class DemandModel extends Model
{
    use HasFactory;
    
    protected $table = 'demands';
    protected $primaryKey = 'dm_id';
    
    protected $fillable = [
        'dm_issue_date',
        'dm_let_no',
        'dm_com_name',
        'dm_com_addr',
        'dm_reg_no',
        'dm_indust_type',
        'country_id',
        'dm_bmi',
        'dm_time_work',
        'dm_sa',
        'dm_job',
        'dm_exp',
        'location',
        'date',
        'created_by',
        'updated_by'
    ];
    
    protected $casts = [
        'dm_issue_date' => 'date',
        'dm_exp' => 'array',
    ];
    
    // Relationship to Industry Type
    public function industryType()
    {
        return $this->belongsTo(inducstryTypeModel::class, 'dm_indust_type', 'inducstry_type_id');
    }
    
    // Relationship to Position DM
    public function positions()
    {
        return $this->hasMany(PositionDmModel::class, 'dm_id', 'dm_id');
    }
    
    // Relationship to User who created
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
    
    // Relationship to User who updated
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
    
    // Relationship to Country
    public function country()
    {
        return $this->belongsTo(\App\Models\country\countryModel::class, 'country_id', 'country_id');
    }
}
