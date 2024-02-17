<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catimage;
use Illuminate\Http\Request;
use Image;

class ImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->file('image');

        // Check if an image file was uploaded
        if ($image) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            // Move the uploaded image to the "category" directory
            $image->move(public_path('category'), $newName);

            // Generate thumbnail
            $sourcepath = public_path('category/') . $newName;
            $savepath = public_path('category/thumb/') . $newName;
            $image = Image::make($sourcepath);
            $image->fit(300, 275)->save($savepath);

            // Save image details to the database
            $imagees = new Catimage();
            $imagees->name = $newName;
            $imagees->save();

            // Retrieve the ID of the saved Catimage
            $image_id = $imagees->id;
        } else {
            // No image uploaded
            $image_id = null;
        }

        // Return JSON response
        return response()->json([
            'status' => true,
            'image_id' => $image_id,
            'imgpath' => asset('category/thumb/'.$newName),
            'msg' => 'Image uploaded successfully'
        ]);
    }

}
