<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOICocSeries extends Model
{
    use HasFactory;
    protected $table="joi_coc_series";
    protected $fillable=[
        'year',
        'series',
    ];
}
