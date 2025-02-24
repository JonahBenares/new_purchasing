<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AOQHead extends Model
{
    use HasFactory;
    protected $table = "aoq_head";
    protected $fillable = [
        'rfq_head_id',
        'aoq_no',
        'aoq_date',
        'date_needed',
        'pr_no',
        'status',
        'user_id',
        'awarded_by',
        'awarded_date',
        'aoq_status',
        'cancelled_by',
        'cancelled_date',
        'aoq_status',
        'prepared_by',
        'received_by',
        'award_recommended_by',
        'recommended_by',
        'approved_by',
    ];

    public function aoq_details(){
        return $this->hasMany(AOQDetails::class);
    }

    public function rfq_head(){
        return $this->belongsTo(RFQHead::class, 'rfq_head_id');
    }
}
