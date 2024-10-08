<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRSeries extends Model
{
    use HasFactory;
    protected $table = "pr_series";
    protected $fillable=[
        'year',
        'series',
    ];
}
