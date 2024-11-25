<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOILaborDetails extends Model
{
    use HasFactory;
    protected $table="joi_labor_details";
    protected $fillable=[
        'joi_head_id',
        'item_no',
        'jor_labor_details_id',
        'item_description',
        'jo_rfq_labor_offer_id',
        'quantity',
        'uom',
        'unit_price',
        'total_cost',
        'currency',
        'reference_joi_no',
        'reference_joi_labor_details_id',
        'status',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason'
    ];
}
