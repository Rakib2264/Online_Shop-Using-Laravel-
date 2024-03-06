<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{

    public function index(Request $request)
    {
        $discountCoupons = DiscountCoupon::latest();
        if (!empty($request->get('keyword'))) {
            $discountCoupons = $discountCoupons->where('name', 'like', '%' . $request->get('keyword') . '%');
            $discountCoupons = $discountCoupons->orwhere('code', 'like', '%' . $request->get('keyword') . '%');
        }
        $discountCoupons = $discountCoupons->paginate(5);
        return view('admin.coupon.list', compact('discountCoupons'));
    }

    public function create()
    {

        return view('admin.coupon.create');
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'des' => 'required',
            'max_uses' => 'required',
            'max_uses_user' => 'required',
            'type' => 'required',
            'discount_amount' => 'required',
            'status' => 'required',
        ]);
        if ($validator->passes()) {

            // starting date must be greater then current date
            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($startAt->lte($now)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start Date must be greater than the current date time']
                    ]);
                }
            }

            // expiry date must be grater than statrt date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if (!$expiresAt->gt($startAt)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expire Date must be greater than the Start Date']
                    ]);
                }
            }

            // If all validations pass, create and save the DiscountCoupon
            $discountCoupon = new DiscountCoupon();
            $discountCoupon->code = $request->code;
            $discountCoupon->name = $request->name;
            $discountCoupon->des = $request->des;
            $discountCoupon->max_uses = $request->max_uses;
            $discountCoupon->max_uses_user = $request->max_uses_user;
            $discountCoupon->type = $request->type;
            $discountCoupon->discount_amount = $request->discount_amount;
            $discountCoupon->min_amount = $request->min_amount;
            $discountCoupon->status = $request->status;
            $discountCoupon->starts_at = $request->starts_at;
            $discountCoupon->expires_at = $request->expires_at;
            $discountCoupon->save();

            // Return success response
            session()->flash('success', 'Discount Coupon Added Successfully.');
            return response()->json([
                'status' => true,
                'msg' => 'Discount Coupon Added Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function show(string $id)
    {
    }


    public function edit(string $id)
    {
        $coupon = DiscountCoupon::find($id);
        if ($coupon == null) {
            return redirect()->route('coupon.index')->with('error', 'Record Not Found');
        }
        return view('admin.coupon.edit', compact('coupon'));
    }


    public function update(Request $request, string $id)
    {
        $discountCoupon = DiscountCoupon::find($id);
        if ($discountCoupon == null) {
            session()->flash('error', 'Record Not Found');
            return response()->json([
                'status' => true,
            ]);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'des' => 'required',
            'max_uses' => 'required',
            'max_uses_user' => 'required',
            'type' => 'required',
            'discount_amount' => 'required',
            'status' => 'required',
        ]);
        if ($validator->passes()) {



            // expiry date must be grater than statrt date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if (!$expiresAt->gt($startAt)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expire Date must be greater than the Start Date']
                    ]);
                }
            }

            // If all validations pass, create and save the DiscountCoupon

            $discountCoupon->code = $request->code;
            $discountCoupon->name = $request->name;
            $discountCoupon->des = $request->des;
            $discountCoupon->max_uses = $request->max_uses;
            $discountCoupon->max_uses_user = $request->max_uses_user;
            $discountCoupon->type = $request->type;
            $discountCoupon->discount_amount = $request->discount_amount;
            $discountCoupon->min_amount = $request->min_amount;
            $discountCoupon->status = $request->status;
            $discountCoupon->starts_at = $request->starts_at;
            $discountCoupon->expires_at = $request->expires_at;
            $discountCoupon->save();

            // Return success response
            session()->flash('success', 'Discount Coupon Updated Successfully.');
            return response()->json([
                'status' => true,
                'msg' => 'Discount Coupon Updated Successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(string $id)
    {
        $discountCoupon = DiscountCoupon::find($id);
        if ($discountCoupon == null) {
            session()->flash('error', 'Record Not Found.');
            return response()->json([
                'status' => true,
            ]);
        }
        $discountCoupon->delete();
        session()->flash('success', 'Record Deleted.');
        return response()->json([
            'status' => true,
        ]);
    }
}
