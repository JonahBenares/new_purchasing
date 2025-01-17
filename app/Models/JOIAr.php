<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIAr extends Model
{
    use HasFactory;
    protected $table="joi_ar";
    protected $fillable=[
        'joi_head_id',
        'ar_no',
        'ar_date',
        'year',
        'series',
    ];
}
