<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
   public function index(){
    $featured_product = Product::orderBy('id','DESC')->where('is_featured','Yes')->where('status',1)->take(8)->get();
    $latest_product = Product::orderBy('id','DESC')->where('status',1)->take(8)->get();

    return view('frontend.home',compact('featured_product','latest_product'));
   }
}
