<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIDrSeries extends Model
{
    use HasFactory;
    protected $table='joi_dr_series';
    protected $fillable=[
        'year',
        'series',
    ];
}
