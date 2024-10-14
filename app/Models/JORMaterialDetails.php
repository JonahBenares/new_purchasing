<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORMaterialDetails extends Model
{
    use HasFactory;
    protected $table="jor_material_details";
    protected $fillable = [
        'jor_head_id',
        'date_needed',
        'item_description',
        'quantity',
        'uom',
        'pn_no',
        'wh_stocks',
        'recom_date',
        'recom_status',
        'cancelled_by',
        'cancelled_date',
        'cancelled_reason',
        'status',
    ];
}
