<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORevisionHead extends Model
{
    use HasFactory;
    protected $table="revised_po_head";
    protected $fillable=[
        'po_head_rev_id',
        'po_head_id',
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
        'cancelled_reason'
    ];
}
