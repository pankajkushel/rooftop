<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;

    protected $fillable = [
        'composition',
    ];

    // Add timestamps if needed
    public $timestamps = true; // Laravel automatically handles the 'created_at' and 'updated_at' columns
}
