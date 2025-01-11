<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDrItems extends Model
{
    use HasFactory;
    protected $table='po_dr_items';
    protected $fillable=[
        'po_dr_id',
        'po_details_id',
        'pr_details_id',
        'rfq_offer_id',
        'quantity',
        'delivered_qty',
        'to_deliver',
        'received_qty',
        'status'
    ];
}
