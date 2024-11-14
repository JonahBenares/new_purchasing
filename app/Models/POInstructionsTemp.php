<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POInstructionsTemp extends Model
{
    use HasFactory;
    protected $table='po_instructions_temp';
    protected $fillable=[
        'po_instruction_id',
        'po_head_id',
        'instructions'
    ];
}
