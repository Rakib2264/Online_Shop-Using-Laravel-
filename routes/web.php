<?php

use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ImagesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\SubCategory;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
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


// for frontend
Route::get('/', [FrontController::class, 'index'])->name('frontend.home');
Route::get('/shop/{categoryslug?}/{subcategoryslug?}', [ShopController::class, 'index'])->name('frontend.shop');
Route::get('/produc_detail/{slug}', [ShopController::class, 'product_detail'])->name('frontend.product_detail');
// cart
Route::get('/cart', [AddToCartController::class, 'cart'])->name('frontend.cart');
Route::post('/add-to-cart', [AddToCartController::class, 'addToCart'])->name('frontend.addToCart');
Route::post('/add-to-cart/update', [AddToCartController::class, 'updatecart'])->name('frontend.updatecart');
Route::delete('/add-to-cart/delete', [AddToCartController::class, 'delete'])->name('frontend.delete');
Route::get('/checkout', [AddToCartController::class, 'checkout'])->name('frontend.checkout');
Route::post('/process-Checkout', [AddToCartController::class, 'processCheckout'])->name('frontend.processCheckout');
Route::get('/thank-you/{orderId}', [AddToCartController::class, 'thanku'])->name('frontend.thanku');
Route::post('/getOrder-Summery', [AddToCartController::class, 'getOrderSummery'])->name('frontend.getOrderSummery');
Route::post('/applyDiscount', [AddToCartController::class, 'applyDiscount'])->name('frontend.applyDiscount');
Route::post('/remove-Discount', [AddToCartController::class, 'removeCoupon'])->name('frontend.removeDiscount');

// auth user interface
Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [AuthController::class, 'login'])->name('frontend.login');
        Route::post('/login/check', [AuthController::class, 'authenticate'])->name('frontend.authenticate');
        Route::get('/register', [AuthController::class, 'register'])->name('frontend.register');
        Route::post('/register/store', [AuthController::class, 'processRegister'])->name('frontend.processRegister');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('frontend.profile');
        Route::get('/logout', [AuthController::class, 'logout'])->name('frontend.logout');
    });
});
// for backend
Route::group(['middleware' => 'admin.guest'], function () {
    Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

    // category
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::get('/categorys', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/upload/image', [ImagesController::class, 'create'])->name('temp-images.create');
    Route::get('/categoryes/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categoryes/{update}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categoryes/{update}/delete', [CategoryController::class, 'destroy'])->name('category.delete');

    // Sub Category
    Route::get('/sub-category/create', [SubCategoryController::class, 'create'])->name('subcategory.create');
    Route::post('/sub-category/store', [SubCategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/sub-categorys', [SubCategoryController::class, 'index'])->name('subcategory.index');
    Route::get('/sub-categoryes/{id}/edit', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
    Route::put('/sub-categoryes/{id}/update', [SubCategoryController::class, 'update'])->name('subcategory.update');
    Route::delete('/sub-categoryes/{id}/delete', [SubCategoryController::class, 'destroy'])->name('subcategory.delete');

    //Brands route
    Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
    Route::get('/brand/{id}/edit', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('/brand/{id}/update', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brand/{id}/delete', [BrandController::class, 'destroy'])->name('brand.delete');

    // Product
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}/delete', [ProductController::class, 'destroy'])->name('product.delete');
    Route::get('/getProducts', [ProductController::class, 'getProducts'])->name('product.getProducts');


    // shipping Route
    Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
    Route::post('/shipping/store', [ShippingController::class, 'store'])->name('shipping.store');
    Route::get('/shipping/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('/shipping/update/{id}', [ShippingController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping/delete/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');

    //  coupon
    Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupon.index');
    Route::get('/coupon/create', [DiscountCodeController::class, 'create'])->name('coupon.create');
    Route::post('/coupon/store', [DiscountCodeController::class, 'store'])->name('coupon.store');
    Route::get('/coupon/edit/{id}', [DiscountCodeController::class, 'edit'])->name('category.edit');
    Route::put('/coupon/update/{id}', [DiscountCodeController::class, 'update'])->name('coupon.update');
    Route::delete('/coupon/delete/{id}', [DiscountCodeController::class, 'destroy'])->name('coupon.delete');



    //if categoey select then subcategory i mean category wize subcategoey show
    Route::get('/product/sub_cat', [ProductSubCategoryController::class, 'index'])->name('product.sub.create');


    // slug
    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug');
});
