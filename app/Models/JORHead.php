<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JORHead extends Model
{
    use HasFactory;
    protected $table="jor_head";
    protected $fillable = [
        'location',
        'jor_no',
        'site_jor',
        'date_prepared',
        'department_id',
        'department_name',
        'dept_code',
        'duration',
        'completion_date',
        'delivery_date',
        'purpose',
        'requestor_id',
        'requestor',
        'urgency',
        'process_code',
        'method',
        'status',
        'user_id',
        'cancelled_by',
        'cancelled_date',
        'general_description',
        'project_activity',
    ];

    public function jo_rfq_head(){
        return $this->hasMany(JORFQHead::class);
    }

    // public function jo_aoq_head(){
    //     return $this->hasMany(JOAOQHead::class);
    // }
}
