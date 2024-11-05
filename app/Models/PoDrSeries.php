<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDrSeries extends Model
{
    use HasFactory;
    protected $table='dr_series';
    protected $fillable=[
        'year',
        'series',
    ];
}
