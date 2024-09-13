<?php

namespace App\Models\customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerModel extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $primaryKey="customer_id";
    protected $fillable = [
        'country_id',
        'customer_name',
        'customer_status',
        'customer_note',
    ];
}
