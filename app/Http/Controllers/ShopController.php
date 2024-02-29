<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subcategorySlug = null)
    {

        $categorySelected = '';
        $subcategorySelected = '';
        $brandsArray = [];
        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
        }

        $categories = Category::orderBy('name', 'asc')->with('sub_category')->where('status', 1)->get();
        $brands = Brand::orderBy('name', 'asc')->where('status', 1)->get();
        $products = Product::where('status', 1);

        // Apply Filters here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);

            $categorySelected = $category->id;
        }

        if (!empty($subcategorySlug)) {
            $subcategory = SubCategory::where('slug', $subcategorySlug)->first();
            $products = $products->where('sub_category_id', $subcategory->id);

            $subcategorySelected = $subcategory->id;
        }
        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')),100000]);

            }



        }


        $pricemax =(intval($request->get('price_max')) == 0 )? 1000 : $request->get('price_max');
        $pricemin = intval($request->get('price_min'));




       if ($request->get('sort') != '') {
        if ($request->get('sort') == 'latest') {
            $products = $products->orderBy('id','desc');
        }elseif($request->get('sort') == 'price_asc'){
            $products = $products->orderBy('price','asc');
        }else{
            $products = $products->orderBy('price','desc');

        }
       }else{
        $products = $products->orderBy('price','desc');
       }
       $sort =  $request->get('sort');

        $products = $products->get();
        // $products = Product::orderBy('title','desc')->where('status',1)->get();
        return view('frontend.shop', compact('categories', 'brands', 'products', 'categorySelected', 'subcategorySelected', 'brandsArray','pricemin','pricemax','sort'));
    }
}
