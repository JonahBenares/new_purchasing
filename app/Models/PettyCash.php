<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;
    protected $table='petty_cash';
    protected $fillable=[
        'pr_head_id',
        'pr_no',
        'prepared_by',
        'reviewed_by',
        'recommended_by',
        'approved_by',
        'approved_date',
        'remarks',
        'user_id'
    ];
}
