<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQMaterialOffers extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_material_offer";
    protected $fillable = [
        'jo_rfq_head_id',
        'jo_rfq_vendor_id',
        'jo_rfq_material_details_id',
        'jor_material_details_id',
        'jor_no',
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

    public function jo_rfq_material_details(){
        return $this->belongsTo(JORFQMaterialDetails::class, 'jo_rfq_material_details_id');
    }

    public function jo_rfq_vendor(){
        return $this->belongsTo(JORFQVendor::class, 'jo_rfq_vendor_id');
    }
}
