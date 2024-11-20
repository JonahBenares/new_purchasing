<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIRevisionDRLaborlDetails extends Model
{
    use HasFactory;
    protected $table="revised_joi_dr_labor";
    protected $fillable=[
        'joi_head_rev_id',
        'joi_dr_rev_id',
        'joi_dr_id',
        'joi_labor_details_id',
        'jor_labor_details_id',
        'jo_rfq_labor_offer_id',
        'quantity',
    ];
}
