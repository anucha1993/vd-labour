<?php

namespace App\Models\files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class listFileModel extends Model
{
    use HasFactory;
    protected $table = 'list_file';
    protected $primaryKey="list_fil_id";
    protected $fillable = [
        'list_file_name',
        'list_file_note',
        'list_file_status',
        'file_manager_id',
    ];
}
