<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIInstructions extends Model
{
    use HasFactory;
    protected $table='joi_instructions';
    protected $fillable=[
        'joi_head_id',
        'instructions'
    ];
}
