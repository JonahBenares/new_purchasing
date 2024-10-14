<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQSeries extends Model
{
    use HasFactory;
    protected $table = "rfq_series";
    protected $fillable = [
        'year',
        'series'
    ];
}