<?php

namespace App\Models\files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class labourFileModel extends Model
{
    use HasFactory;
    protected $table = 'labour_file';
    protected $primaryKey="labour_file_id";
    protected $fillable = [
        'labour_file_name',
        'labour_file_path',
        'list_file_id',
        'labour_id',
        'labour_passport_number',
    ];
}
