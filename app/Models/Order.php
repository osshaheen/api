<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    //payment_method 1-cash on delivery , 2-bank transfere
    use HasFactory;
//    protected $appends = ['string_status','string_payment_method'];
    protected $fillable = ['client_id','status','address_id','billing_details_id','order_total_price','admin_fees','promocode_id','wallet_id','payment_method'];

    public function getStringPaymentMethodAttribute(){
        if($this->payment_method==1){
            return 'الدفع عند الاستلام';
        }elseif($this->payment_method==2){
            return 'تحويل بنكي';
        }
    }
    public function getStringStatusAttribute(){
        if($this->status==1){
            return 'جاري الموافقه على الطلب';
        }elseif($this->status==2){
            return 'جاري الشحن';
        }elseif($this->status==3){
            return 'في الطريق';
        }elseif($this->status==4){
            return 'تم التوصيل';
        }elseif($this->status==5){
            return 'مرفوض';
        }elseif($this->status==0){
            return 'سلة';
        }
    }
    public function getDeliveryCostAttribute(){
        return $this->address ? $this->address->city_delivery_cost : 0;
    }
    public function client(){
        return $this->belongsTo(User::class,'client_id');
    }
    public function getClientNameAttribute(){
        return $this->client ? $this->client->name : '';
    }
    public function getClientMobileAttribute(){
        return $this->client ? $this->client->mobile : '';
    }
    public function getCurrencyAttribute(){
        return $this->client ? $this->client->currency : '';
    }
    public function address(){
        return $this->belongsTo(Address::class,'address_id');
    }
    public function getStreetAddressAttribute(){
        return $this->address ? $this->address->street_address : '';
    }
    public function billingAddress(){
        return $this->belongsTo(BillingAddress::class,'billing_details_id');
    }
    public function promocode(){
        return $this->belongsTo(PromoCode::class,'promocode_id');
    }
    public function getOrderTotalPriceAfterPromocodeAttribute(){
        return $this->order_total_price - $this->coupon_discount;
    }
    public function getPromocodeDiscountPercentageAttribute(){
        return $this->promocode ? $this->promocode->discount_percentage : 0;
    }
    public function getCouponDiscountAttribute(){
        return $this->order_total_price*($this->promocode ? $this->promocode->discount_percentage : 0);
    }
    public function getOrderTotalPriceAttribute(){
        return $this->order_products->count() ? $this->order_products->sum('order_quantity_price') : 0;
    }
    public function wallet(){
        return $this->belongsTo(Wallet::class,'wallet_id');
    }
    public function getIsVendorRevenuesPaidAttribute(){
        return $this->wallet ? ($this->wallet->is_paid == 1 ? 'نعم' : 'لا') : 'لا' ;
    }
    public function order_products(){
        return $this->hasMany(OrderProduct::class,'order_id');
    }
    public function getOrderDateAttribute(){
        return $this->created_at ? $this->created_at->format('Y-m-d') : '';
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
                $orders_ids = DB::table('products')
                    ->select('order_products.*')->distinct('order_products.order_id')
                    ->Join('order_products','products.id','=','order_products.product_id')
                    ->where('products.seller_id',$user->id)
                    ->get()->pluck('order_id');
                return $builder->whereIn('id',$orders_ids);
            }
        });
    }
}
