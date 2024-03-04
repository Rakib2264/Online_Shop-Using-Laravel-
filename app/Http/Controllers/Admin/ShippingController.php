<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{


    public function create()
    {
        $countries = Country::get();
        $shippingcharges = ShippingCharges::leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        return view('admin.shpping.create', compact('countries', 'shippingcharges'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->messages(),
            ]);
        } else {
            $shipping = new ShippingCharges();
            $shipping->country_id = $request->country_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping added successfully');

            return response()->json([
                'status' => 'success',
                'message' => 'Shipping Added',
            ]);
        }
    }





    public function delete($id)
    {
        $shippingCharge = ShippingCharges::find($id);
        $shippingCharge->delete();

        session()->flash('success', 'Shipping deleted successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Shipping deleted',
        ]);
    }

}
