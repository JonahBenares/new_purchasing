<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQSeries extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_series";
    protected $fillable = [
        'year',
        'series'
    ];
}
