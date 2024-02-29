<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
   public function index(){
    $categories = Category::orderBy('name','asc')->with('sub_category')->where('status',1)->get();
    $brands = Brand::orderBy('name','asc')->where('status',1)->get();
    $products = Product::orderBy('title','desc')->where('status',1)->get();
     return view('frontend.shop',compact('categories','brands','products'));
   }
}
