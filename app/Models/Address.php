<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['first_name','last_name','state','apartment','unit','company_name','city_id','street_address','zip_code','phone','email'];
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function getCityNameAttribute(){
        return $this->city ? $this->city->name : '';
    }
    public function getCityDeliveryCostAttribute(){
        return $this->city ? $this->city->delivery_cost : '';
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('permissions', function (Builder $builder) {
            if(Auth::guest()){
                return $builder;
            }
            $user = Auth::user();
            if($user->type == 0){
                return $builder;
            }elseif ($user->type == 1){
                $addresses_ids = DB::table('orders')->select('orders.*')->distinct('orders.address_id')
                    ->Join('order_products','orders.id','=','order_products.order_id')
                    ->Join('products','products.id','=','order_products.product_id')
                    ->where('products.seller_id',$user->id)
                    ->get()->pluck('address_id');
                return $builder->whereIn('id',$addresses_ids);
            }
        });
    }
}
