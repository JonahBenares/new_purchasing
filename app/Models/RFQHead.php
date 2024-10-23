<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQHead extends Model
{
    use HasFactory;
    protected $table = "rfq_head";
    protected $fillable = [
        'pr_head_id',
        'pr_no',
        'rfq_name',
        'rfq_no',
        'rfq_date',
        'user_id',
        'status'
    ];

    public function pr_head(){
        return $this->belongsTo(PRHead::class, 'pr_head_id');
    }

    public function rfq_vendor(){
        return $this->hasMany(RFQVendor::class);
    }

    public function aoq_head(){
        return $this->hasMany(AOQHead::class);
    }
}
