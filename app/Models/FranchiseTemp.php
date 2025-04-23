<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'registerationType',
        'name',
        'email',
        'mobile',
        'alt_mobile',
        'employees',
        'address',
        'pincode',
        'city',
        'state',
        'country',
        'status'
    ];
}

