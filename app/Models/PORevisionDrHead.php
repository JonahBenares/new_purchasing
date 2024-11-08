<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORevisionDrHead extends Model
{
    use HasFactory;
    protected $table='revised_po_dr';
    protected $fillable=[
        'po_head_rev_id',
        'po_dr_id',
        'po_head_id',
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
        'cancelled_reason',
        'revision_no'
    ];
}
