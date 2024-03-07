<?php

use App\Models\Category;
use App\Models\ProductImages;

function getCategory()
{
    return Category::where('showHome', 'Yes')
        ->with('sub_category')
        ->where('status', 1)
        ->orderBy('name', 'ASC')
        ->get();
}

function getProductImage($productId){

    return ProductImages::where('products_id',$productId)->first();
}

?>
