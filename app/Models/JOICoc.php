<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JOICoc extends Model
{
    use HasFactory;
    protected $table="joi_coc";
    protected $fillable=[
        'joi_head_id',
        'coc_no',
        'approved_by',
        'checked_by',
        'warranty',
        'date_prepared',
        'saved',
        'user_id',
    ];
}
