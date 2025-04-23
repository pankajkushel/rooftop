<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'franchise_id',
        'name',
        'email',
        'alt_mobile',
        'employees',
        'registerationType',
        'company_name',
        'address',
        'pincode',
        'city',
        'state',
        'country',
        'mobile'
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function local_appointment()
    {
        return $this->belongsTo(Appointment::class,'pincode','pincode');
    }


}
