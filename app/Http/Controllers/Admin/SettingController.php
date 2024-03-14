<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.users.changepassword');
    }
    public function processChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
        $admin = User::where('id', Auth::guard('admin')->id())->first();

        if ($validator->passes()) {
            if (!Hash::check($request->old_password, $admin->password)) {
                session()->flash('error', 'Your Old Password Is Incorrect, Please Try Again');
                return response()->json([
                    'status' => true,
                    'errors' => $validator->errors()
                ]);
            } else {
                User::where('id', Auth::guard('admin')->id())->update([
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
}
