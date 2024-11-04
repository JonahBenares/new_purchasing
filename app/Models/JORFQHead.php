<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQHead extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_head";
    protected $fillable = [
        'jor_head_id',
        'jor_no',
        'rfq_name',
        'rfq_no',
        'rfq_date',
        'status',
        'user_id'
        
    ];

    public function jor_head(){
        return $this->belongsTo(JORHead::class, 'jor_head_id');
    }

    public function jo_rfq_vendor(){
        return $this->hasMany(JORFQVendor::class);
    }

    // public function jo_aoq_head(){
    //     return $this->hasMany(JOAOQHead::class);
    // }
}
