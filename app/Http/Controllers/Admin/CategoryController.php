<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Catimage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:categories,slug',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'failed',
            'errors' => $validator->messages()
        ]);
    }

    $category = new Category();
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->status = $request->status;
    $category->showHome = $request->showHome;
    $category->save();

    if (!empty($request->image_id)) {
        $catimage = Catimage::find($request->image_id);

        if ($catimage) {
            $ext = pathinfo($catimage->name, PATHINFO_EXTENSION);
            $newImageName = $category->id . '.' . $ext;
            $sourcePath = public_path('category') . '/' . $catimage->name;
            $destinationPath = public_path('up/cat') . '/' . $newImageName;
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath);
                $category->image = $newImageName;
                $category->save();
            }
        }
    }

    session()->flash('success', 'Category Added Successfully');
    return response()->json([
        'status' => 'success',
        'msg' => 'Category Added Successfully'
    ]);

   }
    public function edit(Request $request,$id){
        $category = Category::find($id);
        if(empty($category)){
            return redirect()->route('category.index');
        }
      return view('admin.category.edit',compact('category'));


    }

    public function update(Request $request , $id){

        $category = Category::find($id);
        if(empty($category)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'msg' => 'category not found'
            ]);
         }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->messages()
            ]);
        }

         $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save();



        session()->flash('success', 'Category Updated Successfully');
        return response()->json([
            'status' => 'success',
            'msg' => 'Category Added Successfully'
        ]);
    }

    public function destroy($id,Request $request){
        $category = Category::find($id);
        session()->flash('error', 'Error');

        if (empty($category)) {
            return response()->json([
                'status'=>true,
                'message'=>'error',

             ]);
        }


        File::delete(public_path().'category/'.$category->image);
        File::delete(public_path().'/up/cat/'.$category->image);

        $category->delete();

       session()->flash('success', 'Category Deleted');
       return response()->json([
        'msg' => 'Category Added Successfully'
    ]);

    }
}
