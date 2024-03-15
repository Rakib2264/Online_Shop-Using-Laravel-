<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

    public function sendContactEmail(Request $request){

        $validator = Validator::make($request->all(),[

            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required|min:10',
        ]);
        if ($validator->passes()) {

            //send email here
            $mailData = [

                'name'=>$request->name,
                'email'=>$request->email,
                'subject'=>$request->subject,
                'message'=>$request->message,
                'mail_subject'=>'You Have Received A Contact Email',
            ];

            $admin = User::where('id',1)->first();
            Mail::to($admin)->send(new ContactEmail($mailData));
            session()->flash('success','Thanks for contacting us , we will get back to you soon.');
            return response()->json([
                'status'=>true,
             ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }

    }
}
