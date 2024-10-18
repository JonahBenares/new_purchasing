<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AOQHead extends Model
{
    use HasFactory;
    protected $table='aoq_head';
    protected $fillable = [
        'status'
    ];
}
