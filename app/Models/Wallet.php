<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $appends = ['orders_count'];
    use HasFactory;
    protected $fillable = ['total','is_paid','user_id'];
    public function getOrdersCountAttribute(){
        return $this->orders->count();
    }
    public function orders(){
        return $this->hasMany(Order::class,'wallet_id');
    }
    public function getCurrentWalletJsonAttribute(){
        return [
            'orders_count'=>$this->orders_count,
            'total'=>$this->total,
            'is_paid'=>$this->is_paid
        ];
    }
    public function media(){
        return $this->morphOne(Media::class,'mediable');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
