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
}
