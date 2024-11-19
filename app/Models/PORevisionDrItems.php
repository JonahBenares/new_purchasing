<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORevisionDrItems extends Model
{
    use HasFactory;
    protected $table='revised_po_dr_items';
    protected $fillable=[
        'po_head_rev_id',
        'po_dr_rev_id',
        'po_dr_id',
        'po_details_id',
        'pr_details_id',
        'rfq_offer_id',
        'quantity',
        'status'
    ];
}
