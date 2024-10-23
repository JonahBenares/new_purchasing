<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQOffers extends Model
{
    use HasFactory;
    protected $table = "rfq_offers";
    protected $fillable = [
        'rfq_head_id',
        'rfq_vendor_id',
        'rfq_details_id',
        'pr_details_id',
        'pr_no',
        'offer_no',
        'offer',
        'remaining_qty',
        'uom',
        'unit_price',
        'currency',
        'remarks',
        'awarded_by',
        'status',
    ];

    public function rfq_details(){
        return $this->belongsTo(RFQDetails::class, 'rfq_details_id');
    }

    public function rfq_vendor(){
        return $this->belongsTo(RFQVendor::class, 'rfq_vendor_id');
    }
}
