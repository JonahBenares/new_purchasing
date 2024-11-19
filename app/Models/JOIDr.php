<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIDr extends Model
{
    use HasFactory;
    protected $table="joi_dr";
    protected $fillable=[
        'joi_head_id',
        'jor_head_id',
        'joi_no',
        'jor_no',
        'dr_no',
        'dr_date',
        'site_pr',
        'delivery_date',
        'status',
        'cancelled_by',
        'cancelled_date	',
        'cancelled_reason',
        'user_id',
    ];
}
