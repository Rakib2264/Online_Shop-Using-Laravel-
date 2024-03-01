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
        // Initialize variables
        $categorySelected = '';
        $subcategorySelected = '';
        $brandsArray = [];

        // Check if 'brand' parameter is present in the request
        if (!empty($request->get('brand'))) {
            // If present, split the brands by comma and store in $brandsArray
            $brandsArray = explode(',', $request->get('brand'));
        }

        // Retrieve all categories with their subcategories
        $categories = Category::orderBy('name', 'asc')->with('sub_category')->where('status', 1)->get();
        // Retrieve all brands
        $brands = Brand::orderBy('name', 'asc')->where('status', 1)->get();
        // Retrieve products which are active
        $products = Product::where('status', 1);

        // Apply filters based on category
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        // Apply filters based on subcategory
        if (!empty($subcategorySlug)) {
            $subcategory = SubCategory::where('slug', $subcategorySlug)->first();
            $products = $products->where('sub_category_id', $subcategory->id);
            $subcategorySelected = $subcategory->id;
        }

        // Apply filters based on brands
        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        // Apply filters based on price range
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {
                // If max price is set to 1000, include all prices above the min price
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 100000]);
            }
        }

        // Get the price range values
        $pricemax = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $pricemin = intval($request->get('price_min'));

        // Apply sorting based on user selection
        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                // Sort products by latest
                $products = $products->orderBy('id', 'desc');
            } elseif ($request->get('sort') == 'price_asc') {
                // Sort products by price ascending
                $products = $products->orderBy('price', 'asc');
            } else {
                // Sort products by price descending (default)
                $products = $products->orderBy('price', 'desc');
            }
        } else {
            // If no sorting preference is provided, default to sorting by price descending
            $products = $products->orderBy('price', 'desc');
        }

        // Get the sort value
        $sort = $request->get('sort');

        // Retrieve the final list of products after applying all filters and sorting
        $products = $products->get();

        // Pass all necessary data to the view
        return view('frontend.shop', compact('categories', 'brands', 'products', 'categorySelected', 'subcategorySelected', 'brandsArray', 'pricemin', 'pricemax', 'sort'));
    }
}
