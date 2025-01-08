<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORLaborDetails extends Model
{
    use HasFactory;
    protected $table="jor_labor_details";
    protected $fillable = [
        'jor_head_id',
        'scope_of_work',
        'quantity',
        'uom',
        'unit_cost',
        'total_cost',
        'recom_date',
        'recom_status',
        'status',
        'cancelled_by',
        'cancelled_date',
        'cancelled_reason',
    ];

    public function jo_rfq_labor_details(){
        return $this->hasMany(JORFQLaborDetails::class);
    }
}
