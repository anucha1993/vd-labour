<?php

namespace App\Models\labours;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIDresultsModel extends Model
{
    use HasFactory;
    protected $table = 'cid_results';
    protected $primaryKey="cid_results_id";
    protected $fillable = [
       'cid_results_name',];
}
