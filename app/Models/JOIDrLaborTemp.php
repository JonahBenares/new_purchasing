<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIDrLaborTemp extends Model
{
    use HasFactory;
    protected $table="joi_dr_labor_temp";
    protected $fillable=[
        'joi_dr_id',
        'joi_labor_details_id',
        'jor_labor_details_id',
        'jo_rfq_labor_offer_id',
        'quantity',
    ];
}
