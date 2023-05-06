<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\section_products\updateMethodResource;
use App\Http\Resources\Api\V1\section_products\indexMethodResource;
use App\Http\Resources\Api\V1\section_products\showMethodResource;
use App\Http\Resources\Api\V1\section_products\storeMethodResource;
use App\Http\Requests\V1\sectionProducts\newSectionProductRequest;
use App\Http\Requests\V1\sectionProducts\updateSectionProductRequest;
use App\Models\Section;
use App\Models\SectionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($section_id)
    {
        if($section_id == 3){
            $sectionProducts = SectionProduct::
            with(['section','product.pictures'])
                ->where('section_id',$section_id)
                ->where('status',1)
                ->get();
            $results = indexMethodResource::collection($sectionProducts)->response()->getData();
            $results1['data'] = $results;
            $results1['success'] = true;
        }else{
            $sectionProducts = SectionProduct::
            with(['section','product.pictures'])
                ->where('section_id',$section_id)
                ->paginate(10);
            $results1 = indexMethodResource::collection($sectionProducts)->response()->getData();
            $results1->success = true;
        }
        return $results1;//->additional(['success'=>true]);
    }
    public function getAllSections(){
        $sectionProducts = SectionProduct::
        with(['section','product.pictures'])
            ->paginate(10);
        $results1 = indexMethodResource::collection($sectionProducts)->response()->getData();
        $results1->success = true;
        return $results1;//->additional(['success'=>true]);
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
    public function store(newSectionProductRequest $request)
    {
//        dd($request);
        if($request->section_id == 3){
            SectionProduct::where('section_id',3)->update(['status'=>0]);
        }
        $sectionProduct = SectionProduct::create($request->all());
        $sectionProduct->load(['section','product.pictures']);
        return new storeMethodResource($sectionProduct);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sectionProduct = SectionProduct::find($id);
        if($sectionProduct){
            $sectionProduct->load(['section','product.pictures']);
//            dd(Auth::user());
            return new showMethodResource($sectionProduct);
        }
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
    public function update(updateSectionProductRequest $request,SectionProduct $sectionProduct)
    {
        $sectionProduct->update($request->all());
        $sectionProduct->load(['section','product.pictures']);
        return new updateMethodResource($sectionProduct);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SectionProduct $sectionProduct)
    {
        //
    }
}
