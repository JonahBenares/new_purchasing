<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIRfd extends Model
{
    use HasFactory;
    protected $table="joi_rfd";
    protected $fillable=[
        'joi_head_id',
        'rfd_no',
        'joi_no',
        'jor_no',
        'rfd_date',
        'due_date',
        'check_due',
        'check_name',
        'company',
        'bank_no',
        'pay_to',
        'mode',
        'notes',
        'sub_total',
        'ewt_perc',
        'ewt_amount',
        'ewt',
        'vat',
        'vat_amount',
        'payment_amount',
        'payment_description',
        'retention_perc',
        'retention_amount',
        'balance',
        'status',
        'cancelled_by',
        'cancelled_date',
        'cancelled_reason',
        'checked_by',
        'endorsed_by',
        'approved_by',
        'noted_by',
        'user_id'
    ];
}
