<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Catimage;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('productmages');

        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->get('keyword') . '%');
        }
        $products = $products->paginate(5);
        //  dd($products);
        return view('admin.product.list', compact('products'));
    }
    public function create()
    {
        $categories = Category::orderBy('name', "DESC")->get();
        $brands = Brand::orderBy('name', "DESC")->get();
        return view('admin.product.create', compact('categories', 'brands'));
    }
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category_id' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        // Additional validation rule for quantity if track_qty is set to 'Yes'
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->messages()
            ]);
        } else {
            // Create a new product instance and populate its attributes
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand_id;
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';


            // Save the product
            $product->save();

            // Save product images
            if (!empty($request->imageArray)) {
                foreach ($request->imageArray as $key => $image_id) {
                    $imageinfo = Catimage::find($image_id);
                    if ($imageinfo) {
                        // Extract extension from image name
                        $extArry = explode('.', $imageinfo->name);
                        $ext = end($extArry);

                        // Create a new ProductImages instance
                        $productImage = new ProductImages();
                        $productImage->products_id = $product->id;

                        // Generate a unique image name
                        $imageName = $product->id . '-' . $key . '-' . time() . '.' . $ext;
                        $productImage->image = $imageName;

                        // Save the product image record
                        $productImage->save();

                        // Resize and save the image in large size
                        $sourcepath = public_path('category/') . $imageinfo->name;
                        $dpath_large = public_path('products/large/') . $imageName;
                        'Image'::make($sourcepath)->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($dpath_large);

                        // Resize and save the image in small size
                        $dpath_small = public_path('products/small/') . $imageName;
                        'Image'::make($sourcepath)->fit(300, 280)->save($dpath_small);
                    }
                }
            }

            // Flash message for success
            session()->flash('info', 'Product added successfully');

            // Return success response
            return response()->json([
                'status' => 'success',
                'msg' => 'Product saved successfully',
            ]);
        }
    }

    public function edit($id, Request $request)
    {

        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->route('product.index')->with('error', 'Product not found');
        }

        // Fetch Product Image
        $productimg = ProductImages::where('products_id', $product->id)->get();
        // end
        $subCategory = SubCategory::where('category_id', $product->category_id)->get();
        $categories = Category::orderBy('name', "DESC")->get();
        $brands = Brand::orderBy('name', "DESC")->get();

        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->get();
        }

        return view('admin.product.edit', compact('categories', 'brands', 'product', 'subCategory', 'productimg', 'relatedProducts'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category_id' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        // Additional validation rule for quantity if track_qty is set to 'Yes'
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'errors' => $validator->messages()
            ]);
        } else {
            // Create a new product instance and populate its attributes

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand_id;
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';

            // Save the product
            $product->save();

            // Save product images
            if (!empty($request->imageArray)) {
                foreach ($request->imageArray as $key => $image_id) {
                    $imageinfo = Catimage::find($image_id);
                    if ($imageinfo) {
                        // Extract extension from image name
                        $extArry = explode('.', $imageinfo->name);
                        $ext = end($extArry);

                        // Create a new ProductImages instance
                        $productImage = new ProductImages();
                        $productImage->products_id = $product->id;

                        // Generate a unique image name
                        $imageName = $product->id . '-' . $key . '-' . time() . '.' . $ext;
                        $productImage->image = $imageName;

                        // Save the product image record
                        $productImage->save();

                        // Resize and save the image in large size
                        $sourcepath = public_path('category/') . $imageinfo->name;
                        $dpath_large = public_path('products/large/') . $imageName;
                        'Image'::make($sourcepath)->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($dpath_large);

                        // Resize and save the image in small size
                        $dpath_small = public_path('products/small/') . $imageName;
                        'Image'::make($sourcepath)->fit(300, 280)->save($dpath_small);
                    }
                }
            }

            // Flash message for success
            session()->flash('info', 'Product Updated successfully');

            // Return success response
            return response()->json([
                'status' => 'success',
                'msg' => 'Product Updated successfully',
            ]);
        }
    }

    public function destroy($id, Request $request)
    {

        $product = Product::find($id);

        if (empty($product)) {
            session()->flash('error', 'Producted Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $productimage = ProductImages::where('products_id', $id)->get();
        if (!empty($productimage)) {
            foreach ($productimage as $productimage) {
                File::delete(public_path('product/large/' . $productimage->image));
                File::delete(public_path('product/small/' . $productimage->image));
            }
            ProductImages::where('products_id', $id)->delete();
        }
        $product->delete();

        session()->flash('success', 'Producted Deleted');
        return response()->json([
            'status' => true,
            'msg' => 'Product Deleted',
        ]);
    }

    public function getProducts(Request $request)
    {

        $tempProduct = [];

        if ($request->term != "") {

            $products = Product::where('title', 'like', '%' . $request->term . '%')->get();
            if ($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }

    public function productRating(Request $request)
    {
        $ratings = ProductRating::select('product_ratings.*', 'products.title as productTitle')->orderBy('product_ratings.created_at', 'DESC');
        $ratings = $ratings->leftJoin('products', 'products.id', 'product_ratings.product_id');
        if ($ratings->get('keyword') != "") {
            $ratings = $ratings->where('products.title', 'like', '%' . $request->get('keyword') . '%');
            $ratings = $ratings->orwhere('product_ratings.username', 'like', '%' . $request->get('keyword') . '%');
        }
        $ratings = $ratings->paginate(10);
        return view('admin.product.ratings', compact('ratings'));
    }

    public function changeRatingStatus(Request $request)
    {

        $productrating = ProductRating::find($request->id);
        $productrating->status = $request->status;
        $productrating->save();
        session()->flash('success', 'Status Changed');
        return response()->json([
            'status' => true
        ]);
    }
}
