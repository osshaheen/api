<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Countries\NewCountryRequest;
use App\Http\Requests\V1\Countries\UpdateCountryRequest;
use App\Http\Resources\Api\V\Countries\destroyCountryMethod;
use App\Http\Resources\Api\V1\Countries\indexMethodResource;
use App\Http\Resources\Api\V1\Countries\showMethodResource;
use App\Http\Resources\Api\V1\Countries\storeMethodResource;
use App\Http\Resources\Api\V1\Countries\updateMethodResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        return  new indexMethodResource(Country::all());
        return ['data'=>indexMethodResource::collection(Country::all()),'success'=>true];
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
    public function store(NewCountryRequest $request)
    {
        $country = Country::create($request->all());
        return new storeMethodResource($country);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return new showMethodResource($country);
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
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all());
        return new updateMethodResource($country);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        if (Auth::user()->type == 0) {
            $country->load('users.country', 'cities.country');

            if ($country->users->count()) {
                return response()->json(['users' => destroyCountryMethod::collection($country->users), 'message' => 'لا يمكن حذف بيانات الدولة لوجود مستخدمين مرتبطين بالدولة', 'success' => false]);
            } elseif ($country->cities->count()) {
                return response()->json(['cities' => destroyCountryMethod::collection($country->cities), 'message' => 'لا يمكن حذف بيانات الدولة لوجود مدن مرتبطة بالدولة', 'success' => false]);
            } else {
                $country->delete();
                return response()->json(['message' => 'تم حذف بيانات الدولة بنجاح', 'success' => true]);
            }
        }else{
            return response()->json(['message' => 'صلاحيات الحذف للمدير فقط', 'success' => false]);
        }
    }
}
