<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryPropertyValues\newCategoryPropertyValueRequest;
use App\Http\Requests\V1\CategoryPropertyValues\updateCategoryPropertyValueRequest;
use App\Http\Resources\Api\V1\CategoryPropertyValues\indexMethodResource;
use App\Http\Resources\Api\V1\CategoryPropertyValues\storeMethodResource;
use App\Http\Resources\Api\V1\CategoryPropertyValues\updateMethodResource;
use App\Models\CategoryPropertyValue;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryPropertyValuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $properties = CategoryPropertyValue::
            with(['property','product'])
            ->where('product_id',$product->id)
            ->get();
        $results = indexMethodResource::collection($properties);//->response()->getData();
        return ['data'=>$results,'success'=>true];

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
    public function store(newCategoryPropertyValueRequest $request)
    {
        $value = CategoryPropertyValue::create($request->only('property_id','product_id','value'));
        $value->load('property','product');
        return new storeMethodResource($value);
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryPropertyValue $categoryPropertyValue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryPropertyValue $categoryPropertyValue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateCategoryPropertyValueRequest $request, CategoryPropertyValue $categoryPropertyValue)
    {
        $categoryPropertyValue->update($request->only('property_id','product_id','value'));
        $categoryPropertyValue->load('property','product');
        return new updateMethodResource($categoryPropertyValue);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryPropertyValue $categoryPropertyValue)
    {
        //
    }
}
