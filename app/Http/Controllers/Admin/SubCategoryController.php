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
            'slug' => 'required',
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
}
