<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminders extends Model
{
    use HasFactory;
    protected $table="reminders";
    protected $fillable=[
        'reminder_due_date',
        'reminder_desc',
        'status',
        'assigned_to',
        'employee_name',
        'date_finished',
        'user_id',
    ];
}
