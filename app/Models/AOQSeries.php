<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AOQSeries extends Model
{
    use HasFactory;
    protected $table = "aoq_series";
    protected $fillable = [
        'year',
        'series'
    ];
}
