<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORfdSeries extends Model
{
    use HasFactory;
    protected $table='po_rfd_series';
    protected $fillable=[
        'year',
        'series',
    ];
}
