<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQVendorTerms extends Model
{
    use HasFactory;
    protected $table = "rfq_vendor_terms";
    protected $fillable = [
        'rfq_vendor_id',
        'terms',
    ];
}
