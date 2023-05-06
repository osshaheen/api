<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\revenues\indexMethodResource;
use App\Models\Product;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevenuesController extends Controller
{
    public function getRevenuesSum(){

        if(Auth::user()->type == 0){
            $orders = DB::table('orders')
                ->select(
                    'orders.id as order_id',
                    'products.title as product_title',
                    'users.name as client_name',
                    DB::raw('
                    (
                        CASE WHEN promo_codes.discount_percentage is null THEN 0 ELSE promo_codes.discount_percentage
                        END
                    ) AS discount_percentage1'),
                    DB::raw('(order_products.order_quantity * products.price) as total_price')
                )
                ->join('order_products','order_products.order_id','=','orders.id')
                ->join('products','order_products.product_id','=','products.id')
                ->join('users','orders.client_id','=','users.id')
                ->leftjoin('promo_codes','orders.promocode_id','=','promo_codes.id')
                ->where('orders.status',4)
                ->get();
        }elseif(Auth::user()->type == 1){
            $orders = DB::table('orders')
                ->select(
                    'orders.id as order_id',
                    'products.title as product_title',
                    'users.name as client_name',
                    DB::raw('
                    (
                        CASE WHEN promo_codes.discount_percentage is null THEN 0 ELSE promo_codes.discount_percentage
                        END
                    ) AS discount_percentage1'),
                    DB::raw('(order_products.order_quantity * products.price) as total_price')
                )
                ->join('order_products','order_products.order_id','=','orders.id')
                ->join('products','order_products.product_id','=','products.id')
                ->join('users','orders.client_id','=','users.id')
                ->leftjoin('promo_codes','orders.promocode_id','=','promo_codes.id')
                ->where('orders.status',4)
                ->where('products.seller_id',Auth::id())
                ->get();
        }
        $total_revenues = $orders->sum(function($item){
            return $item->total_price - ($item->total_price*$item->discount_percentage1);
        });
        return response()->json(['total_revenues'=>$total_revenues,'success'=>true]);
    }
    public function getRevenuesDetails(){
        if(Auth::user()->type == 0){
            $orders = DB::table('orders')
            ->select(
        'orders.id as order_id',
                'products.title as product_title',
                'users.name as client_name',
                'order_products.order_quantity as quantity',
                'products.price as product_price',
                'father_category.admin_profit_percentage',
                DB::raw('
                    (
                        CASE WHEN promo_codes.discount_percentage is null THEN 0 ELSE promo_codes.discount_percentage
                        END
                    ) AS discount_percentage1'),
                DB::raw('(order_products.order_quantity * products.price) as total_price')
            )
            ->join('order_products','order_products.order_id','=','orders.id')
            ->join('products','order_products.product_id','=','products.id')
            ->join('categories','products.sub_category_id','=','categories.id')
            ->join('categories as father_category','father_category.id','=','categories.father_id')
            ->join('users','orders.client_id','=','users.id')
            ->leftjoin('promo_codes','orders.promocode_id','=','promo_codes.id')
            ->where('orders.status',4)
            ->get()->each(function($item){
                $item->total_price = (float) $item->total_price;
                $item->discount_percentage1 = (float) $item->discount_percentage1;
                $item->total_revenue =  $item->total_price - ($item->total_price*$item->discount_percentage1);
            });
        }elseif(Auth::user()->type == 1){
            $orders = DB::table('orders')
                ->select(
                    'orders.id as order_id',
                    'products.title as product_title',
                    'users.name as client_name',
                    'order_products.order_quantity as quantity',
                    'products.price as product_price',
                    'father_category.admin_profit_percentage',
                    DB::raw('
                    (
                        CASE WHEN promo_codes.discount_percentage is null THEN 0 ELSE promo_codes.discount_percentage
                        END
                    ) AS discount_percentage1'),
                    DB::raw('(order_products.order_quantity * products.price) as total_price')
                )
                ->join('order_products','order_products.order_id','=','orders.id')
                ->join('products','order_products.product_id','=','products.id')
                ->join('categories','products.sub_category_id','=','categories.id')
                ->join('categories as father_category','father_category.id','=','categories.father_id')
                ->join('users','orders.client_id','=','users.id')
                ->leftjoin('promo_codes','orders.promocode_id','=','promo_codes.id')
                ->where('orders.status',4)
                ->where('products.seller_id',Auth::id())
                ->paginate(5);
        }
        if($orders->count()) {
            $results = indexMethodResource::collection($orders)->response()->getData();
            $results1['data'] = $results;
            $results1['success'] = true;
        }else{
            $results1['success'] = false;
        }
        return $results1;
    }
    public function getVendorsRevenuesDetails(){
        $orders = DB::table('users')
        ->select(
    'orders.id as order_id',
            'products.title as product_title',
            'users.name as vendor_name',
            'users.id as vendor_id',
            'order_products.order_quantity as quantity',
            'products.price as product_price',
            'father_category.admin_profit_percentage',
            DB::raw('
                (
                    CASE WHEN promo_codes.discount_percentage is null THEN 0 ELSE promo_codes.discount_percentage
                    END
                ) AS discount_percentage1'),
            DB::raw('(order_products.order_quantity * products.price) as total_price')
        )
        ->join('products','users.id','=','products.seller_id')
        ->join('order_products','order_products.product_id','=','products.id')
        ->join('orders','orders.id','=','order_products.order_id')
        ->join('categories','products.sub_category_id','=','categories.id')
        ->join('categories as father_category','father_category.id','=','categories.father_id')
//        ->join('users as clients','orders.client_id','=','clients.id')
        ->leftjoin('promo_codes','orders.promocode_id','=','promo_codes.id')
        ->where('orders.status',4)
        ->get()->each(function($item){
            $total_revenue = (float)$item->total_price - ($item->total_price*$item->discount_percentage1);
            $admin_fees = (float)($item->admin_profit_percentage/100)*$total_revenue;
            $vendor_revenue = $total_revenue - $admin_fees;
            $item->total_price = (float) $item->total_price;
            $item->discount_percentage1 = (float) $item->discount_percentage1;
            $item->total_revenue =  $total_revenue;
            $item->vendor_revenue =  $vendor_revenue;
            $item->admin_fees =  $admin_fees;
        })->groupBy('vendor_name')->toArray();
        $data = array();
        foreach ($orders as $key => $order){
            $vendor_id = isset($order[0]) && !empty($order[0]) ? $order[0]->vendor_id : 0;
            $wallet = Wallet::where('user_id',$vendor_id)->where('is_paid',0)->first();
            $wallet_value = 0;
            if($wallet){
                $wallet_value = $wallet->total;
            }
            $object = [];
            $object['vendor_id'] = $vendor_id;
            $object['vendor_name'] = $key;
            $object['total_revenues'] = (float)array_sum(array_column($order, 'total_revenues'));
            $object['wallet'] = (float)$wallet_value;
            array_push($data,$object);
        }

        if(count($orders)) {
//            $results = indexMethodResource::collection($orders)->response()->getData();
            $results1['data'] = $data;
            $results1['success'] = true;
        }else{
            $results1['success'] = false;
        }
        return $results1;
    }

}
