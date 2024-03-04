<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cart;
use Illuminate\Support\Facades\Validator;

class AddToCartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('productmages')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'msg' => 'Product Not Found'
            ]);
        }

        if ('Cart'::count() > 0) {
            // Check if the product is already in the cart
            $cartContent = 'Cart'::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                    break; // No need to continue the loop if we found the product
                }
            }

            if ($productAlreadyExist) {
                $status = false;
                $msg = $product->title . ' already added in cart';
            } else {
                'Cart'::add($product->id, $product->title, 1, $product->price, ['productmages' => (!empty($product->productmages)) ? $product->productmages->first() : '']);
                $status = true;
                $msg = $product->title . ' added in cart';
                session()->flash('success', 'Product added in cart');
            }
        } else {
            // Cart is empty, simply add the product
            'Cart'::add($product->id, $product->title, 1, $product->price, ['productmages' => (!empty($product->productmages)) ? $product->productmages->first() : '']);
            $status = true;
            $msg = $product->title . ' Product added in cart';
            session()->flash('success', 'Product added in cart');
        }
        return response()->json([
            'status' => $status,
            'msg' => $msg
        ]);
    }

    public function cart()
    {
        $cartContent = 'Cart'::content();
        return view('frontend.cart', compact('cartContent'));
    }
    public function updatecart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        // check qty avaliable in stack
        $itemInfo = 'Cart'::get($rowId);
        $product = Product::find($itemInfo->id);
        if ($product->track_qty == "Yes") {
            if ($qty <= $product->qty) {
                'Cart'::update($rowId, $qty);
                session()->flash('success', 'Cart Updated Successfully');
                $status = true;
            } else {
                session()->flash('error', 'Request qty(' . $qty . ') not avalable in stock.');
                $status = false;
            }
        } else {
            'Cart'::update($rowId, $qty);
            session()->flash('success', 'Cart Updated Successfully');
            $status = true;
        }
        // Will update the quantity

        return response()->json([
            'status' => $status,
            'msg' => 'Cart Updated Successfully'
        ]);
    }

    public function delete(Request $request)
    {
        $rowId = $request->rowId;
        $itemInfo = 'Cart'::get($rowId);

        if ($itemInfo == null) {
            session()->flash('error', 'Item not found in cart');
            return response()->json([
                'status' => false,
                'msg' => 'Item not found in cart'
            ]);
        }

        'Cart'::remove($rowId);
        session()->flash('success', 'Cart Deleted');
        return response()->json([
            'status' => true,
            'msg' => 'Cart Deleted'
        ]);
    }

    public function checkout()
    {
        // If the cart is empty, redirect to the cart page
        if ('Cart'::count() == 0) {
            return redirect()->route('frontend.cart');
        }

        // If the user is not logged in, redirect to the login page
        if (!Auth::check() == false) {
            // Save the intended URL to session if not already set
            // if (!session()->has('url.intended')) {
            //     session(['url.intended' => url()->current()]);
            // }
            // return redirect()->route('frontend.login');
        }
        // session()->forget('url.intended');

        $custumeraddress = CustomerAddress::where('user_id',Auth::user()->id)->first();
        $countrys = Country::orderBy('name', 'asc')->get();

        // If the user is logged in and the cart is not empty, show the checkout page
        return view('frontend.checkout', compact('countrys','custumeraddress'));
    }

    public function processCheckout(Request $request)
    {


        // step -1 Apply Validation
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

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Please fix the error',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
          // step -2 Save User Address
          $user = Auth::user();
          CustomerAddress::updateOrCreate(
              ['user_id' => $user->id],
              [
                  'user_id' => $user->id,
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

        // stem 3 store data in orders table
        if($request->payment_method == 'cod'){
            $shipping = 0;
            $discount = 0;
            $subTotal = 'Cart'::subtotal(2,'.','');
            $grandTotal = $subTotal + $shipping;
            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->discount = $discount;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->apartment = $request->apartment;
            $order->notes = $request->order_notes;
            $order->save();
            // step 4 store order items in order items table

            foreach ('Cart'::content() as $item) {
                $orderitem = new OrderItems();
                $orderitem->product_id = $item->id;
                $orderitem->order_id = $order->id;
                $orderitem->name = $item->name;
                $orderitem->qty = $item->qty;
                $orderitem->price = $item->price;
                $orderitem->total = $item->price*$item->qty;
                $orderitem->save();
             }
             session()->flash('success','Order Saved');
             'Cart'::destroy();

             return response()->json([
                'msg' => 'Order Saved',
                'orderId'=>$order->id,
                'status' => true,
             ]);

        }else{

        }



    }

    public function thanku($id){

        return view('frontend.layouts.thanku',compact('id'));
    }
}
