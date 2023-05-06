<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','order_quantity','price','status','order_id','is_quantity_subtracted'];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function getPromocodeDiscountPercentAttribute(){
        return $this->order ? $this->order->promocode_discount_percentage : 0;
    }
    public function getProductSellingAfterCouponPriceAttribute(){
        return $this->price-($this->price*$this->promocode_discount_percent);
    }
    public function getOrderQuantityPriceAttribute(){
        return ($this->price && $this->order_quantity) ? ($this->price * $this->order_quantity) : 0;
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function getProductTitleAttribute(){
        return $this->product ? $this->product->title : '';
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

    public function getSellerNameAttribute(){
        return $this->product ? $this->product->seller_name : '';
    }
    public function getSellerMobileAttribute(){
        return $this->product ? $this->product->seller_mobile : '';
    }
    public function getSellerCountryAttribute(){
        return $this->product ? $this->product->seller_country : '';
    }
    public function getClientIdAttribute(){
        return $this->order ? $this->order->client_id : '';
    }




    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('permissions', function (Builder $builder){
            if(Auth::guest()){
                return $builder;
            }
            $user = Auth::user();
            if($user->type == 0){
                return $builder;
            }elseif ($user->type == 1){

                $product_ids = DB::table('products')
                    ->select('products.*')//->distinct('products.id')
                    ->where('products.seller_id',$user->id)
                    ->get()->pluck('id');
//                dd($orders_ids,$user->id);
                return $builder->whereIn('product_id',$product_ids);
            }
        });
    }
}
