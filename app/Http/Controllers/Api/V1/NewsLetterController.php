<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\newsletter\newNewsLetterRequest;
use App\Http\Requests\V1\newsletter\updateNewsLetterRequest;
use App\Http\Resources\Api\V1\newsletter\indexMethodResource;
use App\Http\Resources\Api\V1\newsletter\showMethodResource;
use App\Http\Resources\Api\V1\newsletter\storeMethodResource;
use App\Http\Resources\Api\V1\newsletter\updateMethodResource;
use App\Models\NewsLetter;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsletters = NewsLetter::paginate(10);
        return indexMethodResource::collection($newsletters);
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
    public function store(newNewsLetterRequest $request)
    {
        $newsLetter = NewsLetter::create($request->all());
        return new storeMethodResource($newsLetter);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $newsLetter = NewsLetter::find($id);
        if($newsLetter){
            return new showMethodResource($newsLetter);
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
    public function update(updateNewsLetterRequest $request,$id)
    {
        $newsLetter = NewsLetter::find($id);
        $newsLetter->update($request->only('email'));
        return new updateMethodResource($newsLetter);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
