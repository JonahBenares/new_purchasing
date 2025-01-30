<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDetails extends Model
{
    use HasFactory;
    protected $table="po_details";
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

    public function po_head(){
        return $this->belongsTo(POHead::class, 'po_head_id');
    }

     public function pr_details(){
        return $this->belongsTo(PRDetails::class, 'pr_details_id', 'id');
    }
}
