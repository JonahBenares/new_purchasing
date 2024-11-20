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
        'jor_no',
        'vendor_details_id',
        'vendor_name',
        'joi_date',
        'date_needed',
        'completion_work',
        'date_prepared',
        'start_of_work',
        'joi_no',
        'discount',
        'discount_material',
        'vat',
        'vat_amount',
        'grand_total',
        'method',
        'revision_no',
        'rev_approved_by',
        'rev_approved_date',
        'rev_approved_reason',
        'prepared_by',
        'checked_by',
        'recommended_by',
        'approved_by',
        'conforme',
        'status',
        'user_id',
        'cancelled_date',
        'cancelled_by',
        'cancelled_reason',
    ];
}
