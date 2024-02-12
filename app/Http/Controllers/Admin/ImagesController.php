<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catimage;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->file('image');
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;
            $image->move('category/', $newName);
            $imagees = new Catimage();
            $imagees->name = $newName;
            $imagees->save();

            // Retrieve the ID of the saved Catimage
            $image_id = $imagees->id;
        } else {
            $image_id = null;
        }

        return response()->json([
            'status' => true,
            'image_id' => $image_id,
            'msg' => 'Image uploaded successfully'
        ]);
    }
}
