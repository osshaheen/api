<?php
function test(){
    dd(5);
}
function getProductRatingPerClient($product_id){
    $ratings = \App\Models\ProductRatings::
//        where('client_id',\Illuminate\Support\Facades\Auth::id())
        where('product_id',$product_id)->get();
    return ['rating'=>$ratings->sum('rating')/5,'ratings_count'=>$ratings->count()];
}
function getSubCategoriesList($sub_category_id){
    $category = \App\Models\Category::find($sub_category_id);
    if($category) {
        $sub_categories = \App\Models\Category::select('id','name')->where('father_id',$category->father_id)->get();
//        dd($sub_categories->toArray());
        return $sub_categories->toArray();
    }else{
        return [];
    }
}
function getSubCategoriesIdList($father_id){
    $category = \App\Models\Category::find($father_id);
    if($category) {
        $sub_categories = \App\Models\Category::select('id','name')->where('father_id',$category->id)->get()->pluck('id');
        return $sub_categories->toArray();
    }else{
        return [];
    }
}
function getAllPossibleSubCategoriesId($categoryIdList){
    $categories = \App\Models\Category::whereIn('id',$categoryIdList)->get();
    $subCategoriesList = [];
    foreach ($categories as $category){
        if($category->father_id){
            array_push($subCategoriesList,$category->id);
        }else{
            $subCategoriesList = array_merge($subCategoriesList,getSubCategoriesIdList($category->id));
        }
    }
    return $subCategoriesList;
}
