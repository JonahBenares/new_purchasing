<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSeries extends Model
{
    use HasFactory;
    protected $table='po_series';
    protected $fillable=[
        'year',
        'series',
    ];
}
