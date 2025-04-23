<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierCollectionDesign extends Model
{
    use HasFactory;

    // Define mass-assignable columns
    protected $fillable = [
        'design_name', 
        'supplier_id', 
        'supplier_collection_id'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplierCollection()
    {
        return $this->belongsTo(SupplierCollection::class);
    }
}


