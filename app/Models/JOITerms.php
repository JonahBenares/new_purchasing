<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOITerms extends Model
{
    use HasFactory;
    protected $table='joi_terms';
    protected $fillable=[
        'joi_head_id',
        'terms'
    ];
}
