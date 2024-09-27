<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTerms extends Model
{
    use HasFactory;
    protected $table = "vendor_terms";
    protected $fillable = [
        'vendor_head_id',
        'vendor_details_id',
        'order_no',
        'terms',
    ];
}
