<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;

    protected $table = 'zip_codes';

    // Define the fillable fields
    protected $fillable = [
        'zip_code',
        'country',
        'state',
        'city',
    ];

    // Optionally, you can add methods to retrieve specific data
    public function scopeByPincode($query, $pincode)
    {
        return $query->where('zip_code', $pincode);
    }
}
