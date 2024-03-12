<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::latest();

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name','like','%'.$request->get('keyword').'%');
            $users = $users->orwhere('email','like','%'.$request->get('keyword').'%');
            $users = $users->orwhere('phone','like','%'.$request->get('keyword').'%');
        }
        $users = $users->paginate(10);
        return view('admin.users.list',compact('users'));
    }

    public function create(Request $request){

    return view('admin.users.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[

            'name'=>'required',
            'email'=>'required|email|unique:users',
            'phone'=>'required|numeric',
            'status'=>'required',
            'password'=>'required|min:6',
        ]);
        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            $user->password = Hash::make($request->password);
            $user->save();
            session()->flash('success','User Created');
            return response()->json([
                'status'=>true,
                'msg'=>'User Created'
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
