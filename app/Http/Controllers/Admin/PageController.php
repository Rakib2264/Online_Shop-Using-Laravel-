<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = Page::latest();
        if ($request->keyword != '') {
           $pages = $pages->where('name','like','%'.$request->get('keyword').'%');
        }
        $pages = $pages->paginate(10);
       return view('admin.pages.list',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'name'=>'required',
            'slug'=>'required',
         ]);
         if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
         }else{
            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();
            session()->flash('success','Page Created');
            return response()->json([
                'status'=>true,
                'msg'=>'Page Created'
            ]);
         }

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
