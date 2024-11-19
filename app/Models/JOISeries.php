<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOISeries extends Model
{
    use HasFactory;
    protected $table='joi_series';
    protected $fillable=[
        'year',
        'series',
    ];
}
