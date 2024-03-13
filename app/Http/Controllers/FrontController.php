<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Wishlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index()
    {
        $featured_product = Product::orderBy('id', 'DESC')->where('is_featured', 'Yes')->where('status', 1)->take(8)->get();
        $latest_product = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();
        return view('frontend.home', compact('featured_product', 'latest_product'));
    }

    public function addtowishlist(Request $request)
    {
        if (Auth::check() == false) {
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => true,
                'msg' => '<div class=" alert alert-danger">Product Not Found</div>'
            ]);
        }


        Wishlists::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );
        // $wishlist = new Wishlists();
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();


        return response()->json([
            'status' => true,
            'msg' => '<div class=" alert alert-success"><strong>"' . $product->title . '" </strong>Aded In Your Wish List</div>'
        ]);
    }

    public function page($slug)
    {

        $pages = Page::where('slug',$slug)->first();
        if ($pages == null) {
            abort(404);
        }
        return view('frontend.page', compact('pages'));
    }
}
