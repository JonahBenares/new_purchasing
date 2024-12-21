<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoRfdPayments extends Model
{
    use HasFactory;
    protected $table='po_rfd_payments';
    protected $fillable=[
        'po_rfd_id',
        'po_head_id',
        'rfd_date',
        'payment_description',
        'payment_amount',
        'user_id'
    ];
    public function po_rfd(){
        return $this->belongsTo(PORfd::class, 'po_rfd_id');
    }
}
