<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOITermsTemp extends Model
{
    use HasFactory;
    protected $table='joi_terms_temp';
    protected $fillable=[
        'joi_terms_id',
        'joi_head_id',
        'terms'
    ];
}
