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
        'po_dr_details_id',
        'pr_dr_details_id',
        'rfq_offer_id',
        'quantity'
    ];
}
