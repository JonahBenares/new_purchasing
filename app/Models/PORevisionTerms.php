<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PORevisionTerms extends Model
{
    use HasFactory;
    protected $table='revised_po_terms';
    protected $fillable=[
        'po_head_id',
        'terms'
    ];
}
