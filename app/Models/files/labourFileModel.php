<?php

namespace App\Models\files;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class labourFileModel extends Model
{
    use HasFactory;
    protected $table = 'labour_file';
    protected $primaryKey="labour_file_id";
    protected $fillable = [
        'labour_file_name',
        'labour_file_note',
        'labour_file_path',
        'list_file_id',
        'labour_id',
        'labour_passport_number',
        'created_by',
        'updated_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
