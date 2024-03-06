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
        $shippingcharges = ShippingCharges::select('shipping_charges.*','countries.name')
        ->leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        return view('admin.shpping.create', compact('countries', 'shippingcharges'));
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes()) {

            $count = ShippingCharges::where('country_id',$request->country_id)->count();
            if ($count > 0) {
             session()->flash('error', 'Shipping already added');
             return response()->json([
                 'status' => true,
              ]);
            }

            $shipping = new ShippingCharges();
            $shipping->country_id = $request->country_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping Added',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($id){
        $shippingcharge = ShippingCharges::find($id);
        $countries = Country::get();

        return view('admin.shpping.edit',compact('countries','shippingcharge'));
    }

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes()) {

            $shipping = ShippingCharges::find($id);
            $shipping->country_id = $request->country_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping Updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping Updated',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function destroy($id)
    {
        $shippingCharge = ShippingCharges::find($id);
        $shippingCharge->delete();

        session()->flash('success', 'Shipping deleted successfully');

        return response()->json([
            'status' => true,
         ]);
    }

}
