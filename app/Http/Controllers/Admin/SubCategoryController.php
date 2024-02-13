<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index(Request $request){
        $subcategories = SubCategory::latest();
        if(!empty($request->get('keyword'))){
            $categories = $subcategories->with('cats')->where('name','like','%'.$request->get('keyword').'%');
        }
        $subcategories = $subcategories->paginate(5);
        return view('admin.subcategory.list',compact('subcategories'));

       }

    public function create(){
        $categories = Category::orderBy('name','desc')->get();
        return view('admin.subcategory.create',compact('categories'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[

            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug',
            'category' => 'required',
            'status' => 'required'
         ]);

         if ($validator->fails()) {
            return response()->json([
                'status'=>'faild',
                'errors' => $validator->messages()
            ]);

         }else{

            $sucategory = new SubCategory();
            $sucategory->name = $request->name;
            $sucategory->slug = $request->slug;
            $sucategory->category_id = $request->category;
            $sucategory->status = $request->status;
            $sucategory->save();
            session()->flash('success','Sub-Category Added Successfully');
            return response()->json([
                'status'=>'sucat saved',
             ]);

         }

    }

    public function edit(Request $request , $id){
        $subcategories = SubCategory::find($id);
        if(empty($subcategories)){
            session()->flash('error','Sub-Category Data Not Found');
            return redirect()->route('subcategory.index');

        }
        $categories = Category::orderBy('name','desc')->get();
        return view('admin.subcategory.edit',compact('subcategories','categories'));
    }

    public function update(Request $request , $id){

        $sucategory = SubCategory::find($id);

        if (!$sucategory) {
            return response()->json([
                'status' => false,
                'message' => 'Sub-category not found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,' . $sucategory->id . ',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->messages()
            ]);
        }

        $sucategory->name = $request->name;
        $sucategory->slug = $request->slug;
        $sucategory->category_id = $request->category;
        $sucategory->status = $request->status;
        $sucategory->save();

        session()->flash('success', 'Sub-Category Updated Successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Sub-category updated successfully',
        ]);

    }

    public function destroy($id, Request $request){
        $subcategory = SubCategory::find($id);

        if (empty($subcategory)) {
            session()->flash('error', 'Subcategory not available');
            return response()->json([
                'status' => false,
                'message' => 'Subcategory not available'
            ]);
        }

        $subcategory->delete();

        session()->flash('success', 'Subcategory deleted');
        return response()->json([
            'status' => true,
            'message' => 'Subcategory deleted successfully'
        ]);
    }

}
