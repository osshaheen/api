<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\coupons\newCouponRequest;
use App\Http\Requests\V1\coupons\updateCouponRequest;
use App\Http\Resources\Api\V1\coupons\indexMethodResource;
use App\Http\Resources\Api\V1\coupons\showMethodResource;
use App\Http\Resources\Api\V1\coupons\storeMethodResource;
use App\Http\Resources\Api\V1\coupons\updateMethodResource;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = PromoCode::
        withCount(['orders'=>function($query){
            $query->where('status',4);
        }])->paginate(10);
        $results = indexMethodResource::collection($coupons)->response()->getData();
        $results->success = true;
        return $results;//->additional(['success'=>true]);
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
    public function store(newCouponRequest $request)
    {
        $coupon = PromoCode::create($request->all());
        $coupon->load(['orders'=>function($query){
            $query->where('status',4);
        }]);
        return new storeMethodResource($coupon);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coupon = PromoCode::find($id);
        if($coupon){
            $coupon->load(['orders'=>function($query){
                $query->where('status',4);
            }]);
            return new showMethodResource($coupon);
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
    public function update(updateCouponRequest $request,$id)
    {
        $coupon = PromoCode::find($id);
        $coupon->update($request->all());
        return new updateMethodResource($coupon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoCode $promoCode)
    {
        //
    }
}
