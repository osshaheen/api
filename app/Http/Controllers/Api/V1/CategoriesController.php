<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Categories\NewCategoryRequest;
use App\Http\Requests\V1\Categories\updateCategoryRequest;
use App\Http\Resources\Api\V1\categories\indexMethodResource;
use App\Http\Resources\Api\V1\categories\showMethodResource;
use App\Http\Resources\Api\V1\categories\storeMethodResource;
use App\Http\Resources\Api\V1\categories\updateMethodResource;
use App\Http\Resources\Api\V1\subCategoriesList;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['picture'])->whereNull('father_id')
            ->withCount('sons')->paginate(10);
        $results = indexMethodResource::collection($categories)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getSubCategories(Category $category)
    {
//        dd($category->toArray());
        $categories = Category::with(['picture'])
            ->where('father_id',$category->id)->paginate(10);
        $results = indexMethodResource::collection($categories)->response()->getData();
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
    public function store(NewCategoryRequest $request)
    {
        $data = $request->only('name','father_id','admin_profit_percentage');
        if(isset($request->father_id) && !empty($request->father_id)){
            $data['level'] = 2;
        }else{
            $data['level'] = 1;
        }
        $category = Category::create($data);
        if($request->file('asset_file')){
            $old_name = $request->file('asset_file')->getClientOriginalName();
            $extension = $request->file('asset_file')->getClientOriginalExtension();
            $file_name = $request->file('asset_file')->store('public','public');
//            dd($old_name,$extension);
            $media_trigger = 3;
            $category->picture()->delete();
            $category->picture()->create([
                'name'=>$file_name,
                'old_name'=>$old_name,
                'type'=>$extension,
                'media_trigger'=>$media_trigger,
                'is_cover'=>0
            ]);
        }
        return new storeMethodResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new showMethodResource($category);
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
    public function update(updateCategoryRequest $request, Category $category)
    {
        $category->update($request->only('name','father_id','admin_profit_percentage'));
        if($request->file('asset_file')){
            $old_name = $request->file('asset_file')->getClientOriginalName();
            $extension = $request->file('asset_file')->getClientOriginalExtension();
            $file_name = $request->file('asset_file')->store('public','public');
//            dd($old_name,$extension);
            $media_trigger = 3;
            $category->picture()->delete();
            $category->picture()->create([
                'name'=>$file_name,
                'old_name'=>$old_name,
                'type'=>$extension,
                'media_trigger'=>$media_trigger,
                'is_cover'=>0
            ]);
        }
        $category->load('picture');
        return new updateMethodResource($category);
    }
    public function categoriesSelect(){
        $categories = Category::with(['picture'])->withCount('sons')->get();
        $results = indexMethodResource::collection($categories)->response()->getData();
        return ['data'=>$results,'success'=>true];
    }
    public function subCategoriesSelect(Category $category){
        $category->load('sons.father','sons.picture');
        $results = subCategoriesList::collection($category->sons)->response()->getData();
        return ['data'=>$results,'success'=>true];
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else{
            $category->load([
                'sons','sons.picture'
            ]);
            if($category->sons->count()) {
                $results = indexMethodResource::collection($category->sons)->response()->getData();
                return response()->json([
                    'message'=>'لا يمكن حذف التصنيف بسبب وجود تصنيفات فرعية ملحقة به',
                    'sub_categories'=>$results,
                    'success'=>false]);
            }else{

                $products = Product::with([
                    'category.father','seller.country','properties_values','cover_picture','pictures'
                ])->where('sub_category_id',$category->id)->paginate(10,['*'], $pageName = 'page', $page = null,$uri=route('categories.getCategoryRelatedProducts',$category->id));
//                dd($products->toArray());
                if($products->count()) {
                    $results = \App\Http\Resources\Api\V1\products\indexMethodResource::collection($products)->response()->getData();
                    return response()->json([
                        'message'=>'لا يمكن حذف التصنيف بسبب وجود منتجات ملحقة به',
                        'products'=>$results,
                        'success'=>false]);
                }else {
                    $category->delete();
                    return response()->json(['message' => 'تم حذف التصنيف بنجاح', 'success' => true]);
                }
            }
        }
    }
    public function getCategoryRelatedProducts(Category $category){
        $products = Product::with([
            'category.father','seller.country','properties_values','cover_picture','pictures'
        ])
            ->where('sub_category_id',$category->id)
            ->paginate(10,['*'], $pageName = 'page', $page = null,$uri=route('products.index'));
        $results = \App\Http\Resources\Api\V1\products\indexMethodResource::collection($products)->response()->getData();
        return response()->json([
            'products'=>$results,
            'success'=>true
        ]);
    }
}
