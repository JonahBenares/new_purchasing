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
        'offer',
        'uom',
        'unit_price',
        'currency',
        'remarks',
        'awarded_by',
        'status',
    ];
}
