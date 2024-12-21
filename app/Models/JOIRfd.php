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
        'grand_total',
        'balance',
        'status',
        'cancelled_by',
        'cancelled_date',
        'cancelled_reason',
        'checked_by',
        'checked_by_name',
        'endorsed_by',
        'endorsed_by_name',
        'approved_by',
        'approved_by_name',
        'noted_by',
        'noted_by_name',
        'received_by',
        'received_by_name',
        'user_id'
    ];
}
