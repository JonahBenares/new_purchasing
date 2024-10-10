<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQDetails extends Model
{
    use HasFactory;
    protected $table = "rfq_details";
    protected $fillable = [
        'rfq_head_id',
        'rfq_vendor_id',
        'pr_details_id',
        'pr_no',
    ];

    public function rfq_vendor(){
        return $this->belongsTo(RFQVendor::class, 'rfq_vendor_id');
    }

    public function pr_details(){
        return $this->belongsTo(PRDetails::class, 'pr_details_id');
    }

}
