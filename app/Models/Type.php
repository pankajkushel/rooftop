<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    // Specify the table if not using plural name convention
    protected $table = 'types';

    // Specify which columns are mass assignable
    protected $fillable = ['type'];

    // Optionally, add timestamps if you have `created_at` and `updated_at` columns
    public $timestamps = true;
}

