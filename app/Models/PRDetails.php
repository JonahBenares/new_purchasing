<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRDetails extends Model
{
    use HasFactory;
    protected $table = "pr_details";
    protected $fillable = [
        'pr_head_id',
        'date_needed',
        'item_description',
        'quantity',
        'uom',
        'pn_no',
        'wh_stocks',
        'status',
        'recom_date',
        'recom_status',
        'referred_date',
        'referred_by',
        'referred_reason',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason'
    ];

    public function rfq_details(){
        return $this->hasMany(rfq_details::class);
    }
}
