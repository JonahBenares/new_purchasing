<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorHead extends Model
{
    use HasFactory;
    protected $table = "vendor_head";
    protected $fillable = [
        'vendor_name',
        'product_services',
    ];
}
