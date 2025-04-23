<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'type',
        'tally_code',
        'file_number',
        'supplier_name',
        'supplier_collection',
        'supplier_collection_design',
        'design_sku',
        'rubs_martendale',
        'width',
        'image',
        'image_alt',
        'colour',
        'composition',
        'design_type',
        'usage',
        'note',
        'currency',
        'supplier_price',
        'freight',
        'duty_percentage',
        'profit_percentage',
        'gst_percentage',
        'mrp',
        'unit',
    ];

    // Relationships
    public function ProductType()
    {
        return $this->belongsto(ProductType::class, 'product_name', 'id');
    }

    public function Supplier()
    {
        return $this->belongsto(Supplier::class, 'supplier_name', 'id');
    }

    public function SupplierCollection()
    {
        return $this->belongsto(SupplierCollection::class, 'supplier_collection', 'id');
    }

    public function SupplierCollectionDesign()
    {
        return $this->belongsto(SupplierCollectionDesign::class, 'supplier_collection_design', 'id');
    }

    public function Type()
    {
        return $this->belongsto(Type::class, 'type', 'id');
    }

    public function Color()
    {
        return $this->belongsto(Color::class, 'colors','colour', 'id');
    }

    public function Composition()
    {
        return $this->belongsto(Composition::class, 'composition', 'id');
    }

    public function DesignType()
    {
        return $this->belongsto(DesignType::class, 'design_type', 'id');
    }

    public function Usage()
    {
        return $this->belongsto(Usage::class, 'usage_proj', 'id');
    }
}
