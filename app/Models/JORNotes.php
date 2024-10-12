<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORNotes extends Model
{
    use HasFactory;
    protected $table="jor_notes";
    protected $fillable = [
        'jor_head_id',
        'notes',
        'status',
        'cancelled_by',
        'cancelled_date',
    ];
}
