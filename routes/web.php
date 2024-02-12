<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ImagesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'admin'],function(){

    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');


    });

    Route::group(['middleware'=>'admin.auth'],function(){
        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');

        // category
        Route::get('/category/create',[CategoryController::class,'create'])->name('category.create');
        Route::get('/categorys',[CategoryController::class,'index'])->name('category.index');
        Route::post('/category/store',[CategoryController::class,'store'])->name('category.store');
        Route::post('/upload/image',[ImagesController::class,'create'])->name('temp-images.create');
        Route::get('/categoryes/{category}/edit',[CategoryController::class,'edit'])->name('category.edit');
        Route::put('/categoryes/{update}/update',[CategoryController::class,'update'])->name('category.update');
        Route::delete('/categoryes/{update}/delete',[CategoryController::class,'destroy'])->name('category.delete');




        Route::get('/getSlug',function(Request $request){
            $slug = '';
        if (!empty($request->title)) {
           $slug = Str::slug($request->title);
        }
        return response()->json([
            'status'=>true,
            'slug'=>$slug
        ]);

        })->name('getSlug');



    });
});
