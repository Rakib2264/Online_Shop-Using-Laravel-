<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
   public function index(Request $request){
    $categories = Category::latest();
    if(!empty($request->get('keyword'))){
        $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
    }
    $categories = $categories->paginate(5);
    return view('admin.category.list',compact('categories'));

   }

   public function create(){

    return view('admin.category.create');

   }

   public function store(Request $request){
    $validator = Validator::make($request->all(),[

        'name'=>'required',
        'slug'=>'required|unique:categories,slug',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'status'=>'faild',
            'errors'=>$validator->messages()
        ]);
    }else{

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        session()->flash('success','Category Added Successfully');
        return response()->json([
            'msg'=>'Category Added Success'
         ]);

    }

   }
}
