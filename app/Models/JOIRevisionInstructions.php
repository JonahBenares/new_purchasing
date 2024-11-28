<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIRevisionInstructions extends Model
{
    use HasFactory;
    protected $table='revised_joi_instructions';
    protected $fillable=[
        'joi_head_rev_id',
        'joi_head_id',
        'instructions'
    ];
}
