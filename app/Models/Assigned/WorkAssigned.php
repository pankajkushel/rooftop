<?php

namespace App\Models\Assigned;

use Illuminate\Database\Eloquent\Model;

class WorkAssigned extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'description',
        'assigned_date',
        'deadline',
        'completion_date',
    ];
}
