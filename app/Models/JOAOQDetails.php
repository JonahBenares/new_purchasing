<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOAOQDetails extends Model
{
    use HasFactory;
    protected $table = "jo_aoq_details";
    protected $fillable = [
        'jo_aoq_head_id',
        'jo_rfq_vendor_id',
    ];

    public function jo_aoq_head(){
        return $this->belongsTo(JOAOQHead::class, 'jo_aoq_head_id');
    }

    public function jo_rfq_vendor(){
        return $this->belongsTo(JORFQVendor::class, 'jo_rfq_vendor_id');
    }
}
