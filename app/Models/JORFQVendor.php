<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQVendor extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_vendor";
    protected $fillable = [
        'jo_rfq_head_id',
        'jor_no',
        'vendor_details_id',
        'vendor_name',
        'vendor_identifier',
        'canvassed',
        'canvassed_by',
        'canvassed_date',
        'award_status',
        'due_date',
        'prepared_by',
        'noted_by',
        'approved_by',
    ];

    public function vendor_details(){
        return $this->belongsTo(VendorDetails::class, 'vendor_details_id');
    }

    public function jo_rfq_head(){
        return $this->belongsTo(JORFQHead::class, 'jo_rfq_head_id');
    }

    public function jo_rfq_material_details(){
        return $this->hasMany(JORFQMaterialDetails::class);
    }

    public function jo_rfq_labor_details(){
        return $this->hasMany(JORFQLaborDetails::class);
    }

    public function jo_rfq_material_offers(){
        return $this->hasMany(JORFQMaterialOffers::class);
    }

    public function jo_rfq_labor_offers(){
        return $this->hasMany(JORFQLaborOffers::class);
    }

    // public function jo_aoq_details(){
    //     return $this->hasMany(JOAOQDetails::class);
    // }
}
