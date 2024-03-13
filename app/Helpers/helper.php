<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\Page;
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

function orderEmail($orderId, $userType = 'customer')
{
    $order = Order::where('id', $orderId)->with('items')->first();
    if ($userType == 'customer') {
        $subject = 'Thanks for your order';
        $email = $order->email;
    } else {
        $subject = 'You have received an order';
        $email = env('ADMIN_EMAIL');
    }
    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType,
    ];
    Mail::to($email)->send(new OrderEmail($mailData));
}

function getCountry($id)
{
    return Country::where('id', $id)->first();
}

function staticPage(){
    $page = Page::orderBy('name','ASC')->get();
    return $page;
}
