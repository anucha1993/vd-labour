<?php

namespace App\Models\examinations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class examinationRoundModel extends Model
{
    use HasFactory;
    protected $table = 'examination_round';
    protected $primaryKey="examination_round_id";
    protected $fillable = [
        'examination_round_name',
        'examination_round_status',
'examination_round_note'
        
    ];
}
