<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use App\Models\Wishlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('frontend.account.login');
    }

    public function register()
    {
        return view('frontend.account.register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed' // 'confirmed' rule ensures 'password_confirmation' matches 'password'
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();
            session()->flash('success', 'You Have Been Register Successfully.');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            // Proceed with login logic
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }


                return redirect()->route('frontend.profile');
            } else {
                return redirect()->route('frontend.login')->withInput($request->only('email'))->with('error', 'email/pass is incorrect.');
            }
        } else {
            return redirect()->route('frontend.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {

        return view('frontend.account.profile');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('frontend.login')->with('succcess', 'Log Out');
    }
    public function orders(){
        $user_id = Auth::user()->id;
        $orders = Order::where('user_id',$user_id)->orderBy('created_at','desc')->get();
        return view('frontend.account.order',compact('orders'));
    }

    public function orderDetails($id){
        $user_id = Auth::user()->id;
        $order = Order::where('user_id',$user_id)->orderBy('created_at','desc')->first();
        $order_item = OrderItems::where('order_id',$id)->get();
        $order_count = OrderItems::where('order_id',$id)->count();
       return view('frontend.account.order_detail',compact('order','order_item','order_count'));
    }

    public function wishlist(){
        $wishlist = Wishlists::where('user_id',Auth::user()->id)->with('product')->get();
        return view('frontend.account.wishlist',compact('wishlist'));
    }

    public function removeProductFromWishList(Request $request){
       $wishlist = Wishlists::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();

       if ($wishlist  == null) {
        session()->flash('error','Product Already Removed');
        return response()->json([
            'status'=>true,
        ]);
       }else{
       Wishlists::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
       session()->flash('success','Product Removed');
       return response()->json([
           'status'=>true,
       ]);
       }
    }
}
