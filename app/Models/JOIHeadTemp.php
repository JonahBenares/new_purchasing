<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIHeadTemp extends Model
{
    use HasFactory;
    protected $table="joi_head_temp";
    protected $fillable=[
        'joi_head_id',
        'discount',
        'discount_material',
        'vat',
        'vat_amount',
        'vat_in_ex',
        'grand_total',
        'revision_no',
        'checked_by',
        'recommended_by',
        'approved_by',
    ];
}
