<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AOQDetails extends Model
{
    use HasFactory;
    protected $table = "aoq_details";
    protected $fillable = [
        'aoq_head_id',
        'rfq_vendor_id',
    ];

    public function aoq_head(){
        return $this->belongsTo(AOQHead::class, 'aoq_head_id');
    }

    public function rfq_vendor(){
        return $this->belongsTo(RFQVendor::class, 'rfq_vendor_id');
    }
}
