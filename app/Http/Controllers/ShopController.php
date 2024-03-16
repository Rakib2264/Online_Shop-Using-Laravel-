<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_Rating;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subcategorySlug = null)
    {
        // Initialize variables
        $categorySelected = '';
        $subcategorySelected = '';
        $brandsArray = [];
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
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }

        // user interface search

        if (!empty($request->get('search'))) {
            $products = $products->where('title', 'like', '%' . $request->get('search') . '%');
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
        $products = $products->paginate(8);

        // Pass all necessary data to the view
        return view('frontend.shop', compact('categories', 'brands', 'products', 'categorySelected', 'subcategorySelected', 'brandsArray', 'pricemin', 'pricemax', 'sort'));
    }

    public function product_detail($slug)
    {

        $product = Product::where('slug', $slug)
            ->withCount('product_ratings')
            ->withSum('product_ratings','rating')
            ->with(['productmages','product_ratings'])
            ->first();



        if ($product == null) {
            abort(404);
        }

        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->where('status', 1)->get();
        }
        // Rating calclation
        $avgRating = '0.00';
        $avgRatingPer = 0;
        if ($product->product_rating_count > 0) {
            $avgRating = number_format(($product->product_rating_sum_rating/$product->product_rating_count),2);
            $avgRatingPer = ($avgRating*100)/5;

        }

        return view('frontend.product_detail', compact('product','relatedProducts','avgRating','avgRatingPer'));
    }
    public function saveRating(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required|min:3',
            'email' => 'required|email',
            'comment' => 'required|min:10',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);

        }else{

           $count= ProductRating::where('email',$request->email)->count();
           if ($count > 0) {
            session()->flash('error','You Already Rated This Product');
            return response()->json([
                'status'=>true,
             ]);
           }
            $product_rating = new ProductRating();
            $product_rating->product_id = $id;
            $product_rating->username = $request->name;
            $product_rating->email = $request->email;
            $product_rating->comment = $request->comment;
            $product_rating->rating = $request->rating;
            $product_rating->status = 0;
            $product_rating->save();
            session()->flash('success','Thanks for your rating');
            return response()->json([
                'status'=>true,
                'msg'=>'Thanks For Your Rating.'
            ]);

        }
    }
}
