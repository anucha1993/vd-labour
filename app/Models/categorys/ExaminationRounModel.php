<?php

namespace App\Models\categorys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationRounModel extends Model
{
    use HasFactory;

    protected $table = 'examination_rounds';
    protected $primaryKey = 'examination_round_id';

    protected $fillable = [
        'examination_round_name',
        'examination_round_note',
        'examination_round_status',
    ];

    protected $casts = [
        'examination_round_name' => 'date',
    ];
}
