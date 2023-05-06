<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'role',
        'address',
        'mobile',
        'country_id',
        'vendor_bank_account',
        'vendor_wallet_account',
        'blocked',
        'password_reset_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function current_wallet(){
        return $this->hasOne(Wallet::class,'user_id')->where('is_paid',0);
    }
    public function getProfileAttribute(){
        if($this->type == 1){
            return [
                'name'=>$this->name,
                'email'=>$this->email,
                'mobile'=>$this->mobile,
                'type'=>$this->type,
                'type_name'=>'بائع',
                'role'=>$this->role,
                'role_name'=>$this->role == 1 ? 'فردي' : ( $this->role == 2 ? 'نشاط تجاري' : ( $this->role == 3 ? 'شركة' : '' ) ),
                'address'=>$this->address,
                'vendor_bank_account'=>$this->vendor_bank_account,
                'vendor_wallet_account'=>$this->vendor_wallet_account,
                'country_id'=>$this->country_id,
                'country_name'=>$this->country ? $this->country->name : '',
                'profile_picture'=>$this->sellerLogo->count() ? $this->sellerLogo[0]->url : '',
                'current_wallet'=>$this->current_wallet ? $this->current_wallet->current_wallet_json : [],
                'success'=>true
            ];
        }elseif($this->type == 2){
            return [
                'name'=>$this->name,
                'email'=>$this->email,
                'mobile'=>$this->mobile,
                'type'=>$this->type,
                'type_name'=>'مشتري',
                'role'=>$this->role,
                'role_name'=>$this->role == 1 ? 'فردي' : ( $this->role == 2 ? 'نشاط تجاري' : '' ),
                'address'=>$this->address,
                'vendor_bank_account'=>$this->vendor_bank_account,
                'vendor_wallet_account'=>$this->vendor_wallet_account,
                'country_id'=>$this->country_id,
                'country_name'=>$this->country ? $this->country->name : '',
                'profile_picture'=>$this->buyerPicture->count() ? $this->buyerPicture[0]->url : '',
                'success'=>true
            ];
        }else{
            return [
                'name'=>$this->name,
                'email'=>$this->email,
                'mobile'=>$this->mobile,
                'type'=>$this->type,
                'type_name'=>'مدير',
                'address'=>$this->address,
                'vendor_bank_account'=>$this->vendor_bank_account,
                'vendor_wallet_account'=>$this->vendor_wallet_account,
                'country_id'=>$this->country_id,
                'country_name'=>$this->country ? $this->country->name : '',
                'profile_picture'=>$this->adminPicture->count() ? $this->adminPicture[0]->url : '',
                'success'=>true
            ];
        }
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function getCountryNameAttribute(){
        return $this->country ? $this->country->name : '';
    }
    public function getCurrencyAttribute(){
        return $this->country ? $this->country->currency : '';
    }
    public function getCurrencyAbbreviationAttribute(){
        return $this->country ? $this->country->currency_abbreviation : '';
    }
    public function media(){
        return $this->morphMany(Media::class,'mediable');
    }
    public function sellerLogo(){
        return $this->morphMany(Media::class,'mediable')->where('media_trigger',1);
    }
    public function buyerPicture(){
        return $this->morphMany(Media::class,'mediable')->where('media_trigger',2);
    }
    public function adminPicture(){
        return $this->morphMany(Media::class,'mediable')->where('media_trigger',0);
    }
    public function cart(){
        return $this->hasOne(Order::class,'client_id','id')->where('status',0);
    }
    public function orders(){
        return $this->hasMany(Order::class,'client_id','id')->where('status','>',0);
    }
}
