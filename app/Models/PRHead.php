<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRHead extends Model
{
    use HasFactory;
    protected $table = "pr_head";
    protected $fillable = [
        'date_issued',
        'location',
        'pr_no',
        'site_pr',
        'date_prepared',
        'department_id',
        'department_name',
        'dept_code',
        'enduse',
        'purpose',
        'requestor',
        'urgency',
        'process_code',
        'method',
        'status',
        'user_id',
        'cancelled_by',
        'cancelled_date',
        'petty_cash'
    ];

    public function rfq_head(){
        return $this->hasMany(RFQHead::class);
    }
}
