<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingAddress extends Model
{
    use HasFactory;
    protected $fillable = ['first_name','last_name','state','apartment','unit','company_name','city_id','street_address','zip_code','phone','email'];
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function orders(){
        return $this->hasMany(Order::class,'billing_details_id');
    }
    public function getCityNameAttribute(){
        return $this->city ? $this->city->name : '';
    }
    public function getCityDeliveryCostAttribute(){
        return $this->city ? $this->city->delivery_cost : '';
    }
    public function getResourceJsonDataAttribute(){
        return [
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'company_name'=>$this->company_name,
            'city'=>$this->city ? $this->city->name : '',
            'country'=>$this->city ? ($this->city->country ? $this->city->country->name : '') : '',
            'street_address'=>$this->street_address,
            'zip_code'=>$this->zip_code,
            'phone'=>$this->phone,
            'email'=>$this->email
        ];
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
                $addresses_ids = DB::table('orders')->select('orders.*')->distinct('orders.billing_details_id')
                    ->Join('order_products','orders.id','=','order_products.order_id')
                    ->Join('products','products.id','=','order_products.product_id')
                    ->where('products.seller_id',$user->id)
                    ->get()->pluck('billing_details_id');
                return $builder->whereIn('id',$addresses_ids);
            }
        });
    }
}
