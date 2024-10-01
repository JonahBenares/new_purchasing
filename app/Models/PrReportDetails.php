<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrReportDetails extends Model
{
    use HasFactory;
    protected $table = "pr_report_details";
    protected $fillable = [
        'pr_no',
        'pr_details_id',
        'item_description',
        'rfq_offer_id',
        'offer',
        'pr_qty',
        'delivered_qty',
        'po_qty',
        'dpo_qty',
        'rpo_qty',
        'uom',
        'method',
        'status',
        'status_remarks'
    ];
}
