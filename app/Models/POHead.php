<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POHead extends Model
{
    use HasFactory;
    protected $table="po_head";
    protected $fillable=[
        'pr_no',
        'vendor_details_id',
        'vendor_name',
        'po_no',
        'po_date',
        'shipping_cost',
        'handling_fee',
        'discount',
        'vat',
        'vat_amount',
        'vat_percent',
        'vat_in_ex',
        'grand_total',
        'prepared_by',
        'checked_by',
        'recommended_by',
        'approved_by',
        'user_id',
        'revision_no',
        'method',
        'status',
        'internal_comment',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason',
        'approved_date',
        'approved_by_rev',
        'approved_reason',
    ];
}
