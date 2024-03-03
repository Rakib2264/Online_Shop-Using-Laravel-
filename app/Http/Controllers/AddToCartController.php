<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Cart;

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
}
