<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ImagesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\SubCategory;
use App\Http\Controllers\Admin\SubCategoryController;
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

        // Sub Category
        Route::get('/sub-category/create',[SubCategoryController::class,'create'])->name('subcategory.create');
        Route::post('/sub-category/store',[SubCategoryController::class,'store'])->name('subcategory.store');
        Route::get('/sub-categorys',[SubCategoryController::class,'index'])->name('subcategory.index');
        Route::get('/sub-categoryes/{id}/edit',[SubCategoryController::class,'edit'])->name('subcategory.edit');
        Route::put('/sub-categoryes/{id}/update',[SubCategoryController::class,'update'])->name('subcategory.update');
        Route::delete('/sub-categoryes/{id}/delete',[SubCategoryController::class,'destroy'])->name('subcategory.delete');

        //Brands route
        Route::get('/brand/create',[BrandController::class,'create'])->name('brand.create');
        Route::post('/brand/store',[BrandController::class,'store'])->name('brand.store');
        Route::get('/brand/',[BrandController::class,'index'])->name('brand.index');
        Route::get('/brand/{id}/edit',[BrandController::class,'edit'])->name('brand.edit');
        Route::put('/brand/{id}/update',[BrandController::class,'update'])->name('brand.update');
        Route::delete('/brand/{id}/delete',[BrandController::class,'destroy'])->name('brand.delete');

        // Product
        Route::get('/product/create',[ProductController::class,'create'])->name('product.create');
        Route::post('/product/store',[ProductController::class,'store'])->name('product.store');
        Route::get('/product/',[ProductController::class,'index'])->name('product.index');




        //if categoey select then subcategory i mean category wize subcategoey show
        Route::get('/product/sub_cat',[ProductSubCategoryController::class,'index'])->name('product.sub.create');


        // slug

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
