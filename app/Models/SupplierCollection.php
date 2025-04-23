<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierCollection extends Model
{
    use HasFactory;

    protected $table = 'supplier_collections';

    protected $fillable = [
        'collection_name',
        'supplier_id', // Include supplier_id as a fillable field
    ];

    public $timestamps = true;

    /**
     * Define the relationship to the Supplier model.
     * Each collection belongs to one supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

