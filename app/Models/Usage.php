<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    // Define the table name if it's different from the plural of the model name
    protected $table = 'usages';

    // Define the fillable fields (which can be mass-assigned)
    protected $fillable = ['usages'];

    // Optionally, you can add timestamps if you want to use 'created_at' and 'updated_at'
    public $timestamps = true;
}
