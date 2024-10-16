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
        'pr_no',
        'status',
        'user_id',
        'awarded_by',
        'awarded_date',
        'aoq_status',
        'prepared_by',
        'received_by',
        'recommended_by',
        'approved_by',
    ];

    public function aoq_details(){
        return $this->hasMany(AOQDetails::class);
    }

    public function pr_head(){
        return $this->belongsTo(PRHead::class, 'pr_no');
    }
}
