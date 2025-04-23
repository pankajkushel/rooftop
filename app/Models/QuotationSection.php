<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationSection extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $table = 'quotation_sections';

    public function quotation(){
        return $this->belongsto(Quotation::class, 'quotation_id', 'id'); 
    }

    public function items(){
        return $this->hasMany(QuotationItem::class,'section_order','id');
    }
}
