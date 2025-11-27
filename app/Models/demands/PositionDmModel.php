<?php

namespace App\Models\demands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\positions\positionModel;

class PositionDmModel extends Model
{
    use HasFactory;
    
    protected $table = 'position_dm';
    protected $primaryKey = 'position_dm_id';
    
    protected $fillable = [
        'dm_id',
        'position_id',
        'position_dm_amount',
        'position_dm_period',
        'position_dm_age'
    ];
    
    // Relationship to Demand
    public function demand()
    {
        return $this->belongsTo(DemandModel::class, 'dm_id', 'dm_id');
    }
    
    // Relationship to Position
    public function position()
    {
        return $this->belongsTo(positionModel::class, 'position_id');
    }
}
