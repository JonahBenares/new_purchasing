<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIRevisionDRMateriallDetails extends Model
{
    use HasFactory;
    protected $table="revised_joi_dr_material";
    protected $fillable=[
        'joi_head_rev_id',
        'joi_dr_rev_id',
        'joi_dr_id',
        'joi_material_details_id',
        'jor_material_details_id',
        'jo_rfq_material_offer_id',
        'quantity',
        'delivered_qty',
        'to_deliver',
        'status',
    ];
}
