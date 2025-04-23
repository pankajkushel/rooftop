<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table='quotations';

    public function franchise(){
        return $this->belongsto(Franchise::class, 'franchise_id', 'id'); 
    }

    public function appointment(){
        return $this->belongsto(Appointment::class, 'appointment_id', 'id'); 
    }

    public function quotaitonItem(){
        return $this->hasMany(QuotationItem::class,'quotation_id','id');
    }

    public function quotaiton_section(){
        return $this->hasMany(QuotationSection::class,'quotation_id','id');
    }

    // public function quotaitonItem(){
    //     return $this->hasMany(QuotationItem::class,'id','quotation_id');
    // }

}
