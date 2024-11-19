<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOAOQSeries extends Model
{
    use HasFactory;
    protected $table = "jo_aoq_series";
    protected $fillable = [
        'year',
        'series'
    ];
}
