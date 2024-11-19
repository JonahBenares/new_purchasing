<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORFQTerms extends Model
{
    use HasFactory;
    protected $table = "jo_rfq_terms";
    protected $fillable = [
        'jo_rfq_vendor_id',
        'terms',
    ];
}
