<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDetailsTemp extends Model
{
    use HasFactory;
    protected $table="po_details_temp";
    protected $fillable=[
        'po_details_id',
        'po_head_id',
        'pr_details_id',
        'rfq_offers_id',
        'reference_po_no',
        'reference_po_details_id',
        'quantity',
        'total_cost',
        'item_description',
        'uom',
        'unit_price',
        'currency',
    ];
}
