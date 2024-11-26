<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIInstructionsTemp extends Model
{
    use HasFactory;
    protected $table='joi_instructions_temp';
    protected $fillable=[
        'joi_instruction_id',
        'joi_head_id',
        'instructions'
    ];
}
