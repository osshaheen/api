<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPropertyValue extends Model
{
    use HasFactory;
    protected $fillable = ['property_id','product_id','value'];
    public function property(){
        return $this->belongsTo(CategoryProperty::class,'property_id');
    }
    public function getPropertyNameAttribute(){
        return $this->property ? $this->property->property_name : '';
    }
    public function getProductNameAttribute(){
        return $this->property ? $this->product->title : '';
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
