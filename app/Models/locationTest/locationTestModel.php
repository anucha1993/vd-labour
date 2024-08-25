<?php

namespace App\Models\locationTest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class locationTestModel extends Model
{
    use HasFactory;
    protected $table = 'location_test';
    protected $primaryKey="location_test_id";
    protected $fillable = [
        'location_test_name',
        'location_test_status',
    ];
}
