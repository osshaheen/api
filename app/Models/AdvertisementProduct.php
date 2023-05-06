<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdvertisementProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','advertisement_id','status'];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function getCurrencyAttribute(){
        return $this->product ? $this->product->currency : '';
    }
    public function getCurrencyAbbreviationAttribute(){
        return $this->product ? $this->product->currency_abbreviation : '';
    }
    public function getProductNameAttribute(){
        return $this->product ? $this->product->title : '';
    }
    public function getProductPriceAttribute(){
        return $this->product ? $this->product->price : '';
    }
    public function getProductDiscountAttribute(){
        return $this->product ? $this->product->discount_limit : '';
    }
    public function getProductQuantityAttribute(){
        return $this->product ? $this->product->available_quantity : '';
    }
    public function getProductExtraPicturesAttribute(){
//        dd($this->product->pictures->where('is_cover',1)->first());
        return $this->product ?
            (
            $this->product->pictures->count() ?
                (
                $this->product->pictures->where('is_cover',0)->count() ?
                    $this->product->pictures->where('is_cover',0) :
                    []
                )
                :[]
            )
            : [];
    }
    public function getProductCoverAttribute(){
//        dd($this->product->pictures->where('is_cover',1)->first());
        return $this->product ?
            (
            $this->product->pictures->count() ?
                (
                $this->product->pictures->where('is_cover',1)->first() ?
                    $this->product->pictures->where('is_cover',1)->first() :
                    $this->product->pictures->where('is_cover',0)->first()
                )
                :''
            )
            : '';
    }
    public function picture(){
        return $this->morphOne(Media::class,'mediable');
    }





    protected static function booted()
    {
        static::addGlobalScope('permissions', function (Builder $builder){
            $country_id = request()->header('session_country_id');
            $user = Auth::user();
            if(Auth::guest()){
                if(!empty($country_id)) {
                    return $builder->whereHas('product.seller', function ($query) use ($country_id) {
                        $query->where('country_id', $country_id);
                    });
                }else{
                    return $builder;
                }
            }elseif($user->type == 0){
                return $builder;
            }elseif ($user->type == 1){
                return $builder->whereHas('product',function ($query) use($user){
                    $query->where('seller_id',$user->id);
                });
            }elseif($user->type == 2){
                return $builder->whereHas('product.seller',function ($query) use($user){
                    $query->where('country_id',$user->country_id);
                });
            }
        });
    }

}
