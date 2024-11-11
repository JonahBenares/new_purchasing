<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQMaterialDetails extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_material_details";
    protected $fillable = [
        'jo_rfq_head_id',
        'jo_rfq_vendor_id',
        'jor_material_details_id',
        'jor_no',
        'status',
        'remaining_qty',
    ];

    public function jo_rfq_vendor(){
        return $this->belongsTo(JORFQVendor::class, 'rfq_vendor_id');
    }

    public function jor_material_details(){
        return $this->belongsTo(JORMaterialDetails::class, 'jor_material_details_id');
    }

    public function jo_rfq_material_offers(){
        return $this->hasMany(JORFQMaterialOffers::class);
    }
}
