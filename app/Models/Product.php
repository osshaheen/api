<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $appends = ['current_price','discount_percent'];
    protected $fillable = ['title','description','discount_limit','sub_category_id','price','seller_id','available_quantity','is_approved'];
    public function getCurrentPriceAttribute(){
        return isset($this->discount_limit) && !empty($this->discount_limit) ? $this->price - $this->discount_limit : $this->price;
    }
    public function getDiscountPercentAttribute(){
        if($this->price && isset($this->discount_limit) && !empty($this->discount_limit)) {
            $percent = 100 - ((($this->price - $this->discount_limit) * 100) / $this->price);
            $decimal = number_format((float)($percent), 2, '.', '');;
            return (float)$decimal;
        }else{
            return 0.0;
        }
    }
    public function category(){
        return $this->belongsTo(Category::class,'sub_category_id');
    }
    public function getCategoryNameAttribute(){
        return $this->category ? $this->category->name : '';
    }
    public function getCategoryFatherNameAttribute(){
        return $this->category ? $this->category->father_name : '';
    }
    public function seller(){
        return $this->belongsTo(User::class,'seller_id');
    }
    public function wishList(){
        return $this->hasMany(WishList::class);
    }
    public function ratings(){
        return $this->hasMany(ProductRatings::class);
    }
    public function getSellerNameAttribute(){
        return $this->seller ? $this->seller->name : '';
    }
    public function getCurrencyAttribute(){
        return $this->seller ? $this->seller->currency : '';
    }
    public function getCurrencyAbbreviationAttribute(){
        return $this->seller ? $this->seller->currency_abbreviation : '';
    }
    public function getSellerMobileAttribute(){
        return $this->seller ? $this->seller->mobile : '';
    }
    public function getSellerCountryAttribute(){
        return $this->seller ? $this->seller->country_name : '';
    }
    public function properties(){
        return $this->hasMany(CategoryProperty::class,'category_id','sub_category_id');
    }
    public function properties_values(){
        return $this->hasMany(CategoryPropertyValue::class,'product_id');
    }
    public function pictures(){
        return $this->morphMany(Media::class,'mediable')->where('media_trigger',4)->where('is_cover',0);
    }
    public function cover_picture(){
        return $this->morphMany(Media::class,'mediable')->where('media_trigger',4)->where('is_cover',1);
    }
    public function one_cover_picture(){
        return $this->morphOne(Media::class,'mediable')->where('media_trigger',4)->where('is_cover',1);
    }

    public function orders_product(){
        return $this->hasMany(OrderProduct::class,'product_id');
    }



    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('permissions', function (Builder $builder){
            $country_id = request()->header('session_country_id');
            $user = Auth::user();
            if(Auth::guest()){
                if(!empty($country_id)) {
                    return $builder->whereHas('seller',function ($query) use($country_id){
                        $query->where('country_id',$country_id);
                    });
                }else{
                    return $builder;
                }
            }elseif($user->type == 0){
                return $builder;
            }elseif ($user->type == 1){
                return $builder->where('seller_id',$user->id);
            }elseif($user->type == 2){
                return $builder->whereHas('seller',function ($query) use($user){
                    $query->where('country_id',$user->country_id);
                });
            }
        });
    }
}
