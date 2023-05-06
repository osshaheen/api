<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    public function statistics(){
        $product_count = Product::where('is_approved',1)->count();
        $clients = Order::where('status',4)->get()->groupBy('client_id')->count();
        $sales = Order::where('status',4)->count();
        if(!Auth::user()->type) {
            $revenues = Order::where('status', 4)->sum('admin_fees');
        }else{
            $revenues = Wallet::where('user_id', Auth::id())->sum('total');
        }
        return [
            'approved_product_count'=>$product_count,
            'delivered_orders_client_count'=>$clients,
            'delivered_orders_sales'=>$sales,
            'delivered_orders_revenues'=>$revenues,
        ];

    }
    public function getRevenuesReportPerYear($year){
        // get year boundaries
        $year_first_date = Carbon::parse($year.'-01-'.'01')->firstOfYear()->format('Y-m-d');
        $year_last_date = Carbon::parse($year.'-01-'.'01')->lastOfYear()->format('Y-m-d');

        $revenues_bag = [];
        if(!Auth::user()->type) {
            // for Admin Revenue report
            $revenues = Order::
            select('id',DB::raw('DATE_FORMAT(created_at,"%c") as order_year_month'),'admin_fees')
                ->where('status', 4)
                ->where('created_at','>=', $year_first_date)
                ->where('created_at','<=', $year_last_date)
                ->get()
                ->groupBy('order_year_month');
            foreach ($revenues as $key =>$value){
                $revenues_bag[$key] = $value->sum('admin_fees');
            }
        }else{
            // for sellers
            $revenues = Wallet::
            select('id','user_id','is_paid','total',DB::raw('DATE_FORMAT(created_at,"%c") as order_year_month'))
                ->where('user_id', Auth::id())
                ->where('is_paid', 1)
                ->where('created_at','>=', $year_first_date)
                ->where('created_at','<=', $year_last_date)
                ->get()
                ->groupBy('order_year_month');
            foreach ($revenues as $key =>$value){
                $revenues_bag[$key] = $value->sum('total');
            }
        }
        return $revenues_bag;
    }
}
