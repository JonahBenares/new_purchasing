<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIRfdSeries extends Model
{
    use HasFactory;
    protected $table='joi_rfd_series';
    protected $fillable=[
        'year',
        'series',
    ];
}
