<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIDrMaterialTemp extends Model
{
    use HasFactory;
    protected $table="joi_dr_material_temp";
    protected $fillable=[
        'joi_dr_id',
        'joi_material_details_id',
        'jor_material_details_id',
        'jo_rfq_material_offer_id',
        'quantity',
    ];
}
