<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORevisionDetails extends Model
{
    use HasFactory;
    protected $table="revised_po_details";
    protected $fillable=[
        'po_head_id',
        'item_no',
        'pr_details_id',
        'rfq_offers_id',
        'reference_po_details_id',
        'reference_po_no',
        'item_description',
        'quantity',
        'uom',
        'unit_price',
        'total_cost',
        'currency',
        'status',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason'
    ];
}
