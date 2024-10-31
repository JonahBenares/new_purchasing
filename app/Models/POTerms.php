<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POTerms extends Model
{
    use HasFactory;
    protected $table='po_terms';
    protected $fillable=[
        'po_head_id',
        'terms'
    ];
}
