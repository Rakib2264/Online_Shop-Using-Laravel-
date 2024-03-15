<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use App\Models\Wishlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $country = Country::orderBy('name', 'ASC')->get();
        $user = User::where('id', Auth::user()->id)->first();
        $coustomerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        return view('frontend.account.profile', compact('user', 'country', 'coustomerAddress'));
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . ',id',
            'phone' => 'required'
        ]);

        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            session()->flash('success', 'User Profile Updated');
            return response()->json([
                'status' => true,
                'msg' => 'User Profile Updated'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [

            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'country' => 'required',
            'address' => 'required|min:15',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        if ($validator->passes()) {

            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'apartment' => $request->apartment,
                    'notes' => $request->order_notes,
                ]
            );
            session()->flash('success', 'User Address Updated');
            return response()->json([
                'status' => true,
                'msg' => 'User Address Updated'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('frontend.login')->with('succcess', 'Log Out');
    }
    public function orders()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        return view('frontend.account.order', compact('orders'));
    }

    public function orderDetails($id)
    {
        $user_id = Auth::user()->id;
        $order = Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        $order_item = OrderItems::where('order_id', $id)->get();
        $order_count = OrderItems::where('order_id', $id)->count();
        return view('frontend.account.order_detail', compact('order', 'order_item', 'order_count'));
    }

    public function wishlist()
    {
        $wishlist = Wishlists::where('user_id', Auth::user()->id)->with('product')->get();
        return view('frontend.account.wishlist', compact('wishlist'));
    }

    public function removeProductFromWishList(Request $request)
    {
        $wishlist = Wishlists::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();

        if ($wishlist  == null) {
            session()->flash('error', 'Product Already Removed');
            return response()->json([
                'status' => true,
            ]);
        } else {
            Wishlists::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();
            session()->flash('success', 'Product Removed');
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function showchangePasswordForm()
    {
        return view('frontend.account.changepass');
    }
    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->passes()) {

            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();
            if (!Hash::check($request->old_password, $user->password)) {
                session()->flash('error', 'Your Old Password Is Incorrect, Please Try Again');
                return response()->json([
                    'status' => true,
                    'errors' => $validator->errors()
                ]);
            } else {
                User::where('id', $user->id)->update([
                    'password' => Hash::make($request->new_password),
                ]);
                session()->flash('success', 'Password Changed');
                return response()->json([
                    'status' => true,
                ]);
            }
        } else {
            return response()->json([
                'status' => 'faild',
                'errors' => $validator->errors()
            ]);
        }
    }
    public function forgotPassword()
    {
        return view('frontend.account.forgot');
    }

    public function processforgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('frontend.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();


        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
        //  send Email
        $user = User::where('email', $request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You Have Requested To Reset Your Password'
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
        return redirect()->route('frontend.forgotPassword')->with('success', 'Please Check Your Inbox To Reset Your Password');
    }

    public function resetPassword($token)
    {
        $tokenExist = \DB::table('password_reset_tokens')->where('token', $token)->first();
        if ($tokenExist == null) {
            return redirect()->route('frontend.forgotPassword')->with('error', 'Invalid Request');
        }
        return view('frontend.account.resetpassword', [
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request)
    {
        $token = $request->token;
        $tokenObj = \DB::table('password_reset_tokens')->where('token', $token)->first();
        if ($tokenObj == null) {
            return redirect()->route('frontend.forgotPassword')->with('error', 'Invalid Request');
        }
        $user = User::where('email', $tokenObj->email)->first();

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('frontend.resetPassword', $token)->withErrors($validator);
        }
        User::where('id', $user->id)->update([

            'password' => Hash::make($request->new_password)
        ]);
        \DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        return redirect()->route('frontend.login')->with('success', 'You Have Successfully Updated Your Password');
    }
}
