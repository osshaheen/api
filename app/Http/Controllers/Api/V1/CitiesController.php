<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Cities\NewCityRequest;
use App\Http\Requests\V1\Cities\UpdateCityRequest;
use App\Http\Resources\Api\V\cities\destroyCity;
use App\Http\Resources\Api\V1\Cities\indexMethodResource;
use App\Http\Resources\Api\V1\Cities\showMethodResource;
use App\Http\Resources\Api\V1\Cities\storeMethodResource;
use App\Http\Resources\Api\V1\Cities\updateMethodResource;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Country $country)
    {
//        dd(City::all());
        $cities = $country->cities;
//        return  new indexMethodResource(City::all());
        return ['data'=>indexMethodResource::collection($cities),'success'=>true];
//        return indexMethodResource::collection($cities);
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
    public function store(NewCityRequest $request)
    {
        $city = City::create($request->all());
        $city->load('country');
        return  new storeMethodResource($city);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $city->load('country');
        return new showMethodResource($city);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        $city->load('country');
        $city->update($request->all());
        return new updateMethodResource($city);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else{
            $city->load('addresses.city','billingAddresses.city');
            if($city->addresses->count()) {
                return response()->json(['addresses' => destroyCity::collection($city->addresses), 'message' => 'لا يمكن حذف بيانات المدينة لوجد عناوين توصيل مرتبطة بالمدينة', 'success' => false]);
            }elseif($city->billingAddresses->count()){
                return response()->json(['billingAddresses'=>destroyCity::collection($city->billingAddresses),'message'=>'لا يمكن حذف بيانات المدينة لوجد عناوين توصيل مرتبطة بالمدينة','success'=>false]);
            }else{
                $city->delete();
                return response()->json(['message'=>'تم حذف بيانات المدينة بنجاح','success'=>true]);
            }
        }

    }
}
