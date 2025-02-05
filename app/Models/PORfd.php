<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORfd extends Model
{
    use HasFactory;
    protected $table='po_rfd';
    protected $fillable=[
        'po_head_id',
        'pr_no',
        'po_no',
        'rfd_date',
        'apv_no',
        'due_date',
        'check_due',
        'check_name',
        'company',
        'bank_no',
        'pay_to',
        'mode',
        'notes',
        'grand_total',
        'sub_total',
        'balance',
        'status',
        'identifier',
        'show_ewt',
        'ewt',
        'ewt_amount',
        'checked_by',
        'checked_by_name',
        'noted_by',
        'noted_by_name',
        'endorsed_by',
        'endorsed_by_name',
        'approved_by',
        'approved_by_name',
        'received_by',
        'received_by_name',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason',
        'user_id',
    ];
}
