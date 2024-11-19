<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POHeadTemp extends Model
{
    use HasFactory;
    protected $table="po_head_temp";
    protected $fillable=[
        'po_head_id',
        'shipping_cost',
        'handling_fee',
        'discount',
        'vat',
        'vat_amount',
        'vat_percent',
        'vat_in_ex',
        'grand_total',
        'revision_no',
        'internal_comment',
    ];
}
