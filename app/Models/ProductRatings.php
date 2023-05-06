<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRatings extends Model
{
    use HasFactory;
    protected $fillable = ['rating','client_id','product_id','comment'];
    public function getRatingDataAttribute(){
        return [
            'rating'=>$this->rating,
            'comment'=>$this->comment
        ];
    }
}
