<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDr extends Model
{
    use HasFactory;
    protected $table='po_dr';
    protected $fillable=[
        'pr_head_id',
        'po_no',
        'pr_no',
        'site_pr',
        'dr_date',
        'dr_no',
        'status',
        'delivery_date',
        'user_id',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason'
    ];
}