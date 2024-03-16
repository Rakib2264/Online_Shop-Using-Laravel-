<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function productmages(){
        return $this->hasMany(ProductImages::class,'products_id','id');
    }

    public function product_ratings(){
        return $this->hasMany(Product_Rating::class, 'product_id')->where('status', 1);
    }

}
