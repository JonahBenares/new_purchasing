<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDetails extends Model
{
    use HasFactory;
    protected $table = "vendor_details";
    protected $fillable = [
        'vendor_head_id',
        'address',
        'identifier',
        'terms',
        'phone',
        'fax',
        'contact_person',
        'email',
        'tin',
        'type',
        'notes',
        'ewt',
        'vat',
        'status',
    ];

    public function vendor_head(){
        return $this->belongsTo(VendorHead::class, 'vendor_head_id');
    }

    public function rfq_vendor(){
        return $this->hasMany(RFQVendor::class);
    }
}
