<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\billing_addresses\newBillingAddressRequest;
use App\Http\Requests\V1\billing_addresses\updateBillingAddressRequest;
use App\Http\Resources\Api\V1\billing_addresses\indexMethodResource;
use App\Http\Resources\Api\V1\billing_addresses\showMethodResource;
use App\Http\Resources\Api\V1\billing_addresses\storeMethodResource;
use App\Http\Resources\Api\V1\billing_addresses\updateMethodResource;
use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billing_addresses = BillingAddress::
            with(['city.country'])->paginate(10);
        $results = indexMethodResource::collection($billing_addresses)->response()->getData();
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
    public function store(newBillingAddressRequest $request)
    {
        $billing_address = BillingAddress::create($request->all());
        return new storeMethodResource($billing_address);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $billing_address = BillingAddress::find($id);
        if($billing_address){
            return new showMethodResource($billing_address);
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
    public function update(updateBillingAddressRequest $request,$id)
    {
        $billing_address = BillingAddress::find($id);
        $billing_address->update($request->all());
        return new updateMethodResource($billing_address);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingAddress $billingAddress)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else{

            $billingAddress->load([
                'orders',
                'orders.client','orders.order_products.product.seller','orders.order_products.product.pictures',
                'orders.address.city','orders.billingAddress.city','orders.promocode','orders.wallet'
            ]);
            if($billingAddress->orders->count()) {
                $results = \App\Http\Resources\Api\V1\orders\indexMethodResource::collection($billingAddress->orders)->response()->getData();
                return response()->json([
                    'message'=>'يوجد مجموعة من الطلبات مرتبطة بالعنوان ، يرجى حذفها او تغيير عنوانها لكي يتم حذف العنوان',
                    'orders'=>$results,
                    'success'=>false]);
            }else{
                $billingAddress->delete();
                return response()->json(['message'=>'تم حذف المنتج من الاعلان بنجاح','success'=>true]);
            }
        }
    }
}
