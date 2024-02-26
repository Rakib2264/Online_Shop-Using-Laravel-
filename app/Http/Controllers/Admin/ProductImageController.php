<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Image;

class ProductImageController extends Controller
{


    // public function update(Request $request)
    // {


    //     // Retrieve the uploaded image file
    //     $image = $request->file('image');

    //     // Get the file extension
    //     $ext = $image->getClientOriginalExtension();

    //     // Generate a unique image name
    //     $imageName = $request->products_id . '-' . time() . '.' . $ext;

    //     // Resize and save the image in large size
    //     $sourcePath = $image->getPathName();
    //     $largeImagePath = public_path('product/large/') . $imageName;
    //     'Image'::make($sourcePath)->resize(1400, null, function ($constraint) {
    //         $constraint->aspectRatio();
    //     })->save($largeImagePath);

    //     // Resize and save the image in small size
    //     $smallImagePath = public_path('product/small/') . $imageName;
    //     'Image'::make($sourcePath)->fit(300, 280)->save($smallImagePath);

    //     // Save the product image record to the database
    //     $productImage = new ProductImages();
    //     $productImage->products_id = $request->products_id;
    //     $productImage->image = $imageName;
    //     $productImage->save();

    //     // Return a JSON response with success message and image details
    //     return response()->json([
    //         'status' => true,
    //         'image_id' => $productImage->id,
    //         'image_path' => asset('product/small/' . $imageName),
    //         'message' => 'Image updated successfully',
    //     ]);
    // }
}
