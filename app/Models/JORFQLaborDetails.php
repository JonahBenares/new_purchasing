<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQLaborDetails extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_labor_details";
    protected $fillable = [
        'jo_rfq_head_id',
        'jo_rfq_vendor_id',
        'jor_labor_details_id',
        'jor_no',
        'status',
        'remaining_qty',
    ];

    public function jo_rfq_vendor(){
        return $this->belongsTo(JORFQVendor::class, 'rfq_vendor_id');
    }

    public function jor_labor_details(){
        return $this->belongsTo(JORLaborDetails::class, 'jor_labor_details_id');
    }

    public function jo_rfq_labor_offers(){
        return $this->hasMany(JORFQLaborOffers::class);
    }
}
