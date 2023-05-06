<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\approveProductRequest;
use App\Http\Requests\V1\Products\newProductRequest;
use App\Http\Requests\V1\Products\updateProductRequest;
use App\Http\Resources\Api\V1\products\destroyProductMethod;
use App\Http\Resources\Api\V1\products\indexMethodResource;
use App\Http\Resources\Api\V1\products\productPropertiesNameMethodResource;
use App\Http\Resources\Api\V1\products\showMethodResource;
use App\Http\Resources\Api\V1\products\storeMethodResource;
use App\Http\Resources\Api\V1\products\updateMethodResource;
use App\Models\Category;
use App\Models\CategoryProperty;
use App\Models\CategoryPropertyValue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category.father','seller.country','properties_values','cover_picture','pictures'])->paginate(10);
        $results = indexMethodResource::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(newProductRequest $request)
    {
        $data = $request->only('title','description','sub_category_id','available_quantity','price','discount_limit');
        if(Auth::user()->type == 0){
            $data['is_approved'] = $request->is_approved ? $request->is_approved : 0;
            if(isset($request->seller_id)){
                $data['seller_id'] = $request->seller_id;
            }
        }else{
            $data['seller_id'] = Auth::id();
            if(isset($request->discount_limit )){
                $data['discount_limit'] = $request->discount_limit ;
            }
        }
        $product = Product::create($data);
        if(isset($request->properties)){
            foreach ($request->properties as $property){
                $existed_property = CategoryPropertyValue::where('property_id',$property['property_id'])
                    ->where('product_id',$product->id)
                    ->first();
                if($existed_property){
                    $existed_property->update([
                        'value'=>$property['value']
                    ]);
                }else{
                    $product->properties_values()->create([
                        'property_id'=>$property['property_id'],
                        'value'=>$property['value']
                    ]);
                }
            }
        }
        if(!empty($request->is_cover)){
            $old_name = $request->is_cover->getClientOriginalName();
            $extension = $request->is_cover->getClientOriginalExtension();
            $file_name = $request->is_cover->store('public', 'public');
            if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                $product->pictures()->create([
                    'name' => $file_name,
                    'old_name' => $old_name,
                    'type' => $extension,
                    'media_trigger' => 4,
                    'is_cover' => 1
                ]);
            }
        }
//        dd($request->extra_images,$request->files);
        if(!empty($request->extra_images)){
            foreach ($request->extra_images as $key => $image) {
                $old_name = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $file_name = $image->store('public', 'public');
                $is_cover = 0;
                if(!$key && empty($request->is_cover)){
                    $is_cover = 1;
                }
                if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                    $product->pictures()->create([
                        'name' => $file_name,
                        'old_name' => $old_name,
                        'type' => $extension,
                        'media_trigger' => 4,
                        'is_cover' => $is_cover
                    ]);
                }
            }
        }
        $product->load('category.father','seller.country','properties_values','cover_picture','pictures');
        return new storeMethodResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category.father','cover_picture','seller.country','properties_values','pictures']);
//        dd($product->toArray());
        return new showMethodResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateProductRequest $request, Product $product)
    {

        $data = $request->only('title','description','sub_category_id','available_quantity','price','discount_limit');
        if(Auth::user()->type == 0){
            $data['is_approved'] = $request->is_approved ? $request->is_approved : 0;
            if(isset($request->seller_id)){
                $data['seller_id'] = $request->seller_id;
            }
        }else{
            $data['seller_id'] = Auth::id();
            if(isset($request->discount_limit )){
                $data['discount_limit '] = $request->discount_limit ;
            }
        }
        if(isset($request->properties)){
            foreach ($request->properties as $property){
                $existed_property = CategoryPropertyValue::where('property_id',$property['property_id'])
                    ->where('product_id',$product->id)
                    ->first();
                if($existed_property){
                    $existed_property->update([
                        'value'=>$property['value']
                    ]);
                }else{
                    $product->properties_values()->create([
                        'property_id'=>$property['property_id'],
                        'value'=>$property['value']
                    ]);
                }
            }
        }
        if(!empty($request->is_cover)){
            $old_name = $request->is_cover->getClientOriginalName();
            $extension = $request->is_cover->getClientOriginalExtension();
            $file_name = $request->is_cover->store('public', 'public');
            if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                $product->pictures()->create([
                    'name' => $file_name,
                    'old_name' => $old_name,
                    'type' => $extension,
                    'media_trigger' => 4,
                    'is_cover' => 1
                ]);
            }
        }
        if(!empty($request->extra_images)){
            foreach ($request->extra_images as $key => $image) {
//                dd($image,$request->files);
                $old_name = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $file_name = $image->store('public', 'public');
                $is_cover = 0;
                if(!$key && empty($request->is_cover)){
                    $is_cover = 1;
                }
                if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                    $product->pictures()->create([
                        'name' => $file_name,
                        'old_name' => $old_name,
                        'type' => $extension,
                        'media_trigger' => 4,
                        'is_cover' => $is_cover
                    ]);
                }
            }
        }
        $product->load('category.father','seller.country','properties_values','cover_picture','pictures');
        $product->update($data);
        return new updateMethodResource($product);
    }
    public function productPropertiesName(Category $category){
        $category_id = $category->father_id;
        $properties = CategoryProperty::whereIn('category_id',[$category_id,$category->id])->get();
        $properties = productPropertiesNameMethodResource::collection($properties)->response()->getData();
        return response()->json(['data'=>$properties,'success'=>true]);
    }
    public function getAllUnApprovedProducts(){
        $products = Product::with(['category.father','seller.country','properties_values','cover_picture','pictures'])->where('is_approved',0)->paginate(10);
        $results = indexMethodResource::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getAllApprovedProducts(){
        $products = Product::with(['category.father','seller.country','properties_values','cover_picture','pictures'])->where('is_approved',1)->paginate(10);
        $results = indexMethodResource::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getAllRejectedProducts(){
        $products = Product::with(['category.father','seller.country','properties_values','cover_picture','pictures'])->where('is_approved',2)->paginate(10);
        $results = indexMethodResource::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function approveProduct(approveProductRequest $request){
        if(Auth::user()->type == 0) {
            $product = Product::find($request->product_id)->update(['is_approved' => 1]);
            return response()->json(['message' => 'تم الموافقة على المنتج', 'success' => true]);
        }else{
            return response()->json(['message' => 'لا يمكن الموافقة على المنتج من حساب البائع', 'success' => false]);
        }
    }
    public function rejectProduct(approveProductRequest $request){
        if(Auth::user()->type == 0) {
            $product = Product::find($request->product_id)->update(['is_approved'=>2]);
            return response()->json(['message'=>'تم رفض المنتج','success'=>true]);
        }else{
            return response()->json(['message' => 'لا يمكن رفض المنتج من حساب البائع', 'success' => false]);
        }
    }
    public function getAllCategoryFilteredProducts(Category $category){
        if($category->level == 1){
            $products = Product::with(['category.father','seller.country','properties_values','cover_picture','pictures'])
                ->whereIn('sub_category_id',Category::select('id')->where('father_id',$category->id)->get()->pluck('id'))
                ->paginate(10);
            $results = indexMethodResource::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }else{
            $products = Product::with(['category.father','seller.country','properties_values','cover_picture','pictures'])->where('sub_category_id',$category->id)->paginate(10);
            $results = indexMethodResource::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else {
            $product->load(['orders_product.order']);

            if ($product->orders_product->count()) {
                return response()->json(['orders' => destroyProductMethod::collection($product->orders_product), 'message' => 'لا يمكن حذف بيانات المنتج لارتباط طلبيات به', 'success' => false]);
            } else {
                $product->delete();
                return response()->json(['message' => 'تم حذف بيانات المنتج بنجاح', 'success' => true]);
            }
        }
    }
}
