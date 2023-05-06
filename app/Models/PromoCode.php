<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table = 'promo_codes';
    use HasFactory;
    protected $fillable = ['title','status','start_date','end_date','discount_percentage'];
    public function orders(){
        return $this->hasMany(Order::class,'promocode_id');
    }
    public function getCheckValidityAttribute(){
        $current_date = Carbon::today();
        if($current_date->gte(Carbon::parse($this->start_date))&&$current_date->lte(Carbon::parse($this->end_date))){
            return $this;
        }else{
            return response()->json(['message'=>'الكوبون منتهية صلاحيته','success'=>false]);
        }
    }
}
