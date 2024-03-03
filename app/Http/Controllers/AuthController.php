<?php

namespace App\Http\Controllers;

use App\Models\User;
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

                return redirect()->route('frontend.profile');
            } else {
                session()->flash('error', 'email/pass is incorrect.');
                return redirect()->route('frontend.login')->withInput($request->only('email'));
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
}
