<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\ShippingCharges;
use Carbon\Carbon;
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
        $discount = 0;
        // If the cart is empty, redirect to the cart page
        if ('Cart'::count() == 0) {
            return redirect()->route('frontend.cart');
        }

        // If the user is not logged in, redirect to the login page
        if (Auth::check() == false) {
            // Save the intended URL to session if not already set
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('frontend.login');
        }
        session()->forget('url.intended');


        $countrys = Country::orderBy('name', 'ASC')->get();
        $custumeraddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        $subTotal = 'Cart'::subtotal(2, '.', '');
        // apply discount Here
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'parcent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }
        // calculate shipping here
        if ($custumeraddress) {
            $userCountry = $custumeraddress->country_id;
            $shippinginfo = ShippingCharges::where('country_id', $userCountry)->first();
            $totalQty = 0;
            $totalshippingcharge = 0;
            $grandtotal = 0;
            foreach ('Cart'::content() as $item) {
                $totalQty += $item->qty;
            }
            $totalshippingcharge = $totalQty * $shippinginfo->amount;
            $grandtotal = ($subTotal - $discount) + $totalshippingcharge;
        } else {
            $totalshippingcharge = 0;
            $grandtotal = $subTotal - $discount;
        }


        // If the user is logged in and the cart is not empty, show the checkout page
        return view('frontend.checkout', compact('countrys', 'custumeraddress', 'totalshippingcharge', 'grandtotal', 'discount'));
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
        if ($request->payment_method == 'cod') {
            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = 'Cart'::subtotal(2, '.', '');
            // $grandTotal = $subTotal + $shipping;
            // apply discount Here
            if (session()->has('code')) {
                $code = session()->get('code');
                if ($code->type == 'parcent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            // calculate shiping

            $shippingInfo = ShippingCharges::where('country_id', $request->country)->first();
            $totalQty = 0;
            foreach ('Cart'::content() as $item) {

                $totalQty += $item->qty;
            }
            if ($shippingInfo != null) {
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            } else {
                $shippingInfo = ShippingCharges::where('country_id', 'rest_of_world')->first();
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            }



            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->discount = $discount;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
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
                $orderitem->total = $item->price * $item->qty;
                $orderitem->save();
            }
            // send order Email
            orderEmail($order->id);
            session()->flash('success', 'Order Saved');
            'Cart'::destroy();

            session()->forget('code');

            return response()->json([
                'msg' => 'Order Saved',
                'orderId' => $order->id,
                'status' => true,
            ]);
        } else {
        }
    }

    public function thanku($id)
    {

        return view('frontend.layouts.thanku', compact('id'));
    }

    public function getOrderSummery(Request $request)
    {
        $subTotal = 'Cart'::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';
        // apply discount Here
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'parcent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountString = '<div class="mt-4" id="dicsounticonremove">
            <strong>' . session()->get('code')->code . '</strong>
            <a  class="btn btn-sm btn-danger" id="removediscount"><i class="fa fa-times"></i></a>
            </div>';
        }
        if ($request->country_id > 0) {

            $shippingInfo = ShippingCharges::where('country_id', $request->country_id)->first();
            $totalQty = 0;
            foreach ('Cart'::content() as $item) {

                $totalQty += $item->qty;
            }
            if ($shippingInfo != null) {
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            } else {
                $shippingInfo = ShippingCharges::where('country_id', 'rest_of_world')->first();
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal - $discount), 2),
                'discount' => $discount,
                'discountString' => $discountString,
                'shippingCharge' => number_format(0, 2),
            ]);
        }
    }

    public function applyDiscount(Request $request)
    {

        // Find the discount coupon by its code
        $code = DiscountCoupon::where('code', $request->code)->first();

        // Check if the coupon doesn't exist
        if ($code == null) {
            return response()->json([
                'status' => false,
                'msg' => 'Invalid discount coupon'
            ]);
        }

        // Get the current date and time
        $now = Carbon::now();
        // Check if the coupon has a start date
        if ($code->starts_at != "") {
            // Parse the start date of the coupon
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            // Check if the current date is before the start date
            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid discount coupon'
                ]);
            }
        }

        // Check if the coupon has an expiration date
        if ($code->expires_at != "") {
            // Parse the expiration date of the coupon
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            // Check if the current date is after the expiration date
            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid discount coupon'
                ]);
            }
        }
        // max uses check
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();
            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid discount coupon'
                ]);
            }
        }

        // max uses user check
        if ($code->max_uses_user > 0) {

            $couponUsedByUser = Order::where('coupon_code_id', $code->id)->where('user_id', Auth::user()->id)->count();

            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'msg' => 'You already use this coupon'
                ]);
            }
        }
        $subTotal = 'Cart'::subtotal(2, '.', '');
        // minimum amount condiction check
        if ($code->min_amount > 0) {
             if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Your Minimum maount Must Be $'.$code->min_amount.'.',
                ]);
             }
        }



        session()->put('code', $code);
        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
