<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function index(){
        // $admin = Auth::guard('admin')->user();
        // echo "welcome".$admin->name.'<a href="'.route('admin.logout').'">Logout</a>';
        return view('admin.dashboard');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');

    }
}
