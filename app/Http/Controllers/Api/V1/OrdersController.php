<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\orders\indexMethodResource;
use App\Http\Resources\Api\V1\orders\showMethodResource;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status','>',0)->whereNotNull('status')
            ->paginate(10);
        $results = indexMethodResource::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getIsRejectedOrders()
    {
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status',5)->whereNotNull('status')
            ->paginate(10);
        $results = indexMethodResource::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getApprovalPendingOrders()
    {
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status',1)->whereNotNull('status')
            ->paginate(10);
        $results = indexMethodResource::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getIsShippingOrders()
    {
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status',2)->whereNotNull('status')
            ->paginate(10);
        $results = indexMethodResource::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getInRoadOrders()
    {
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status',3)->whereNotNull('status')
            ->paginate(10);
        $results = indexMethodResource::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getDeliveredOrders()
    {
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status',4)->whereNotNull('status')
            ->paginate(10);
        $results = indexMethodResource::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function setOrderIsShippingStatus(Request $request)
    {
        $order = Order::with(['order_products.product'])->find($request->order_id);
        if($order){
            $errors = [];
            foreach ($order->order_products as $order_product){
                if($order_product->order_quantity > $order_product->product->available_quantity){
                    array_push($errors,'الكمية المطلوبة لمنتج '.$order_product->product_title.' تساوي '.$order_product->order_quantity.' ، أكبر من الكمية المتوفرة '.$order_product->product->available_quantity);
                }else {
                    if (!$order_product->is_quantity_subtracted) {
                        $order_product->product->update([
                            'available_quantity' => ($order_product->product->available_quantity - $order_product->order_quantity)
                        ]);
                        $order_product->update([
                            'is_quantity_subtracted' => 1
                        ]);
                    }
                }
            }
            if(empty($errors)) {
                $order->update(['status' => 2]);
                return response()->json(['message' => 'تم تعديل الحالة الى جاري الشحن', 'success' => true]);
            }else{
                return response()->json(['message' => 'توجد منتجات كميتها المطلوبة اكبر من الكمية المتوفرة','errors'=>$errors, 'success' => false]);
            }
        }else{
            return response()->json(['message'=>'الطلبية غير موجودة','success'=>false]);
        }
    }
    public function setOrderInRoadStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order){
            $order->update(['status'=>3]);
            return response()->json(['message'=>'تم تعديل الحالة الى في الطريق','success'=>true]);
        }else{
            return response()->json(['message'=>'الطلبية غير موجودة','success'=>false]);
        }
    }
    public function setOrderDeliveredStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order){
            $user = Auth::user()->load('current_wallet');
            if($user->current_wallet){
                $user->current_wallet->update(['total'=>$user->current_wallet->total + $order->order_total_price_after_promocode]);
                $wallet_id = $user->current_wallet->id;
            }else{
                $wallet_id = $user->current_wallet()->create(['total'=>$order->order_total_price_after_promocode])->id;
            }
            $order->update([
                'status'=>4,
                'wallet_id'=>$wallet_id
            ]);
            return response()->json(['message'=>'تم تعديل الحالة تم التوصيل','success'=>true]);
        }else{
            return response()->json(['message'=>'الطلبية غير موجودة','success'=>false]);
        }
    }
    public function setOrderApprovalPendingStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order){
            $order->update(['status'=>1]);
            return response()->json(['message'=>'تم تعديل الحالة الى في انتظار الموافقة','success'=>true]);
        }else{
            return response()->json(['message'=>'الطلبية غير موجودة','success'=>false]);
        }
    }
    public function setOrderRejectedStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order){
            $order->update(['status'=>5]);
            return response()->json(['message'=>'تم تعديل الحالة الى مرفوض','success'=>true]);
        }else{
            return response()->json(['message'=>'الطلبية غير موجودة','success'=>false]);
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet']);
        $results = new showMethodResource($order);
        $results->success = true;
        return $results;
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else {
            if ($order->status == 1) {
                $order->address->delete();
                $order->billingAddress->delete();
                $order->order_products->delete();
                $order->delete();
                return response()->json(['message' => 'تم حذف الطلبية بنجاح', 'success' => true]);
            } elseif ($order->status == 2 || $order->status == 3) {
                $order->update(['status' => 5]);
                return response()->json(['message' => 'تم الغاء الطلبية بنجاح', 'success' => true]);
            } elseif ($order->status == 4) {
                return response()->json(['message' => 'لا يتم حذف الطبية بعد توصيلها', 'success' => false]);
            } else {
                return response()->json(['message' => 'الطلبية تم اغاؤها مسبقا', 'success' => false]);
            }
        }
    }
}
