<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Categories\NewCategoryRequest;
use App\Http\Requests\V1\category_properties\newCategoryPropertyRequest;
use App\Http\Requests\V1\category_properties\updateCategoryPropertyRequest;
use App\Http\Resources\Api\V\MobileApp\destroyCategoryPropertyResource;
use App\Http\Resources\Api\V1\category_properties\indexMethodResource;
use App\Http\Resources\Api\V1\category_properties\showMethodResource;
use App\Http\Resources\Api\V1\category_properties\storeMethodResource;
use App\Http\Resources\Api\V1\category_properties\updateMethodResource;
use App\Models\Category;
use App\Models\CategoryProperty;
use App\Models\CategoryPropertyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryPropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $categories = CategoryProperty::where('category_id',$category->id)->paginate(10);
        $results = indexMethodResource::collection($categories)->response()->getData();
        $results->success = true;
        $results->category = $category->name;
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
    public function store(newCategoryPropertyRequest $request)
    {
        $data = $request->only('property_name','category_id');
        $category = CategoryProperty::create($data);
        $category->load('category');
        return new storeMethodResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryProperty $categoryProperty)
    {
        return new showMethodResource($categoryProperty);
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
    public function update(updateCategoryPropertyRequest $request, CategoryProperty $categoryProperty)
    {
        $categoryProperty->update($request->only('property_name','category_id'));
        return new updateMethodResource($categoryProperty);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryProperty $categoryProperty)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else {
            if ($categoryProperty->categoryPropertyValues->count()) {
                $categoryPropertyValues = CategoryPropertyValue::with(['property', 'product'])
                    ->where('property_id', $categoryProperty->id)->paginate(10);
                $results = destroyCategoryPropertyResource::collection($categoryPropertyValues)->response()->getData();
                return response()->json([
                    'categoryPropertyValues' => $results,
                    'message' => 'يوجد قيم للخاصية مرتبطة بها ، يرجى حذفها او تعديلها حتى تتمكن من حذف الخاصية',
                    'success' => false
                ]);

            } else {
                $categoryProperty->delete();
                return response()->json(['message' => 'تم حذف الخاصية', 'success' => true]);
            }
        }
    }
}
