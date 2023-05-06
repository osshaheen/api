<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name','delivery_cost','country_id'];
    function country(){
        return $this->belongsTo(Country::class);
    }
    public function getCurrencyAttribute(){
        return $this->country ? $this->country->currency : '';
    }
    public function getCurrencyAbbreviationAttribute(){
        return $this->country ? $this->country->currency_abbreviation : '';
    }
    public function addresses(){
        return $this->hasMany(Address::class);
    }
    public function billingAddresses(){
        return $this->hasMany(BillingAddress::class);
    }
}
