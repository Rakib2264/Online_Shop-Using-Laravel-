<?php

use App\Models\Category;

function getCategory(){
   return Category::where('showHome','Yes')
   ->with('sub_category')
   ->where('status',1)
   ->orderBy('name','ASC')
   ->get();
}

?>
