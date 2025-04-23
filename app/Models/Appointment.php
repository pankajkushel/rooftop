<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function franchise(){
        return $this->belongsto(Franchise::class, 'franchise_id', 'id'); 
    }

    public function local_franchise(){
        return $this->hasMany(Franchise::class, 'city', 'city'); 
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d-m-Y'); // Change the format as needed
    }
    
}
