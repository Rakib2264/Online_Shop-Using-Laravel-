<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){

        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');
        if(!empty($request->get('keyword'))){
            $orders = $orders->where('users.name','like','%'.$request->get('keyword').'%');
            $orders = $orders->orwhere('users.email','like','%'.$request->get('keyword').'%');
            $orders = $orders->orwhere('orders.id','like','%'.$request->get('keyword').'%');
        }
        $orders = $orders->paginate(10);


        return view('admin.orders.list',compact('orders'));

    }

    public function detail($id){
       $order = Order::select('orders.*','countries.name as countryName')
        ->where('orders.id',$id)
        ->leftJoin('countries','countries.id','orders.country_id')
        ->first();
        $orderItems = OrderItems::where('order_id',$id)->get();
        return view('admin.orders.detail',compact('order','orderItems'));
    }
}
