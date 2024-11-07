<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORevisionInstructions extends Model
{
    use HasFactory;
    protected $table='revised_po_instructions';
    protected $fillable=[
        'po_head_id',
        'instructions'
    ];
}
