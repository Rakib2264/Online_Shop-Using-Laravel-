<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\ProductImages;
use Illuminate\Support\Facades\Mail;

function getCategory()
{
    return Category::where('showHome', 'Yes')
        ->with('sub_category')
        ->where('status', 1)
        ->orderBy('name', 'ASC')
        ->get();
}

function getProductImage($productId)
{

    return ProductImages::where('products_id', $productId)->first();
}

function orderEmail($orderId){
  $order = Order::where('id',$orderId)->with('items')->first();
  $mailData = [
    'subject'=>'Thanks for your order',
    'order'=>$order,
  ];
  Mail::to($order->email)->send(new OrderEmail($mailData));

}

function getCountry($id){
   return Country::where('id',$id)->first();
}


