<?php

namespace App\Models\country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countryModel extends Model
{
    use HasFactory;
    protected $table = 'country';
    protected $primaryKey="country_id";
    protected $fillable = [
        'country_name_th',
        'country_name_en',
        'country_status',
        
    ];
}
