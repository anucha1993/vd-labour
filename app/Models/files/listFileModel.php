<?php

namespace App\Models\files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class listFileModel extends Model
{
    use HasFactory;
    protected $table = 'list_file';
    protected $primaryKey="list_file_id";
    protected $fillable = [
        'list_file_name',
        'list_file_note',
        'list_file_status',
        'file_manage_id',
    ];

    public function fileManage()
    {
        return $this->belongsTo(fileManageModel::class, 'file_manage_id', 'file_manage_id');
    }
}
