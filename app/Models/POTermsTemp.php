<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POTermsTemp extends Model
{
    use HasFactory;
    protected $table='po_terms_temp';
    protected $fillable=[
        'po_head_id',
        'terms'
    ];
}
