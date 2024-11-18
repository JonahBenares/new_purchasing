<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIHead extends Model
{
    use HasFactory;
    protected $table="joi_head";
    protected $fillable=[
        'jor_no',
        'vendor_details_id',
        'vendor_name',
        'joi_date',
        'joi_no',
        'shipping_cost',
        'handling_fee',
        'vat',
        'vat_amount',
        'grand_total',
        'method',
        'revision_no',
        'rev_approved_by',
        'rev_approved_date',
        'rev_approved_reason',
        'prepared_by',
        'checked_by',
        'recommended_by',
        'approved_by',
        'status',
        'user_id',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason',
    ];
}
