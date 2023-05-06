<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $appends = ['profit_percentage'];
    protected $fillable = ['name','level','father_id','admin_profit_percentage'];
    public function father(){
        return $this->belongsTo(Category::class,'father_id');
    }
    public function getFatherNameAttribute(){
        return $this->father ? $this->father->name : '';
    }
    public function getProfitPercentageAttribute(){
        return $this->level == 2 ? $this->father->admin_profit_percentage : $this->admin_profit_percentage;
    }
    public function sons(){
        return $this->hasMany(Category::class,'father_id');
    }
    public function properties(){
        return $this->hasMany(CategoryProperty::class,'category_id');
    }
    public function products(){
        return $this->hasMany(Product::class,'sub_category_id');
    }
    public function picture(){
        return $this->morphMany(Media::class,'mediable')->where('media_trigger',3);
    }
}
