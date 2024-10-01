<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQVendor extends Model
{
    use HasFactory;
    protected $table = "rfq_vendor";
    protected $fillable = [
        'rfq_head_id',
        'pr_no',
        'vendor_details_id',
        'vendor_name',
        'award_status',
        'due_date',
        'prepared_by',
        'noted_by',
        'approved_by',
    ];
}
