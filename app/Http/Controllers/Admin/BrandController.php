<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        $brand = Brand::latest();
        if (!empty($request->get('keyword'))) {
            $brand = $brand->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $brand = $brand->paginate(5);
        return view('admin.brand.list', compact('brand'));
    }

    public function create()
    {
        $brand = Brand::orderBy('name', 'desc')->get();
        return view('admin.brand.create', compact('brand'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'faild',
                'errors' => $validator->messages()
            ]);
        } else {

            $brands = new Brand();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();
            session()->flash('success', 'Brand Added Successfully');
            return response()->json([
                'status' => 'brand saved',
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            session()->flash('error', 'Brand Data Not Found');
            return redirect()->route('brand.index');
        }
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {

        $brand = Brand::find($id);

        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',
         ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->messages()
            ]);
        }

        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();

        session()->flash('success', 'Brand Updated Successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Brand updated successfully',
        ]);
    }

    public function destroy($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            session()->flash('error', 'brand not available');
            return response()->json([
                'status' => false,
                'message' => 'brand not available'
            ]);
        }

        $brand->delete();

        session()->flash('success', 'Brand deleted');
        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }
}
