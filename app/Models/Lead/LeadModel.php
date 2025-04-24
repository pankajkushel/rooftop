<?php

namespace App\Models\Lead;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LeadModel extends Model
{
    use HasFactory;
    protected $table = 'lead_models';
 
    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'status',
        'created_by',
        'is_converted'
    ];

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'lead_id', 'id');
    }
}
