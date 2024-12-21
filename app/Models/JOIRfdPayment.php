<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOIRfdPayment extends Model
{
    use HasFactory;
    protected $table='joi_rfd_payment';
    protected $fillable=[
        'joi_rfd_id',
        'joi_id',
        'rfd_date',
        'payment_description',
        'payment_details',
        'payment_amount',
        'subtotal',
        'ewt_vat',
        'ewt_percent',
        'ewt_amount',
        'retention_percent',
        'retention_amount',
        'user_id',
    ];
    public function joi_rfd(){
        return $this->belongsTo(JOIRfd::class, 'joi_rfd_id');
    }
}
