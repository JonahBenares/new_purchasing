<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThingsTodo extends Model
{
    use HasFactory;
    protected $table="things_todo";
    protected $fillable=[
        'todo_description',
        'status',
        'date_finished',
        'user_id',
    ];
}
