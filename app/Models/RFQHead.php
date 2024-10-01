<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFQHead extends Model
{
    use HasFactory;
    protected $table = "rfq_head";
    protected $fillable = [
        'pr_head_id',
        'pr_no',
        'rfq_name',
        'rfq_no',
        'rfq_date',
        'user_id',
    ];
}
