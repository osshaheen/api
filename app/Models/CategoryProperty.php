<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProperty extends Model
{
    use HasFactory;
    protected $fillable = ['property_name','category_id'];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function categoryPropertyValues(){
        return $this->hasMany(CategoryPropertyValue::class,'property_id','id');
    }
}
