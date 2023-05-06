<?php

namespace App\Http\Controllers\MobileAppApi\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MobileApp\addOrderDeliveryAddressRequest;
use App\Http\Requests\V1\MobileApp\addProductToCartRequest;
use App\Http\Requests\V1\MobileApp\submitOrderProductRatingRequest;
use App\Http\Resources\Api\V1\advertisementProducts\indexMethodResource;
use App\Http\Resources\Api\V1\MobileApp\getAdvertisementByItsLocationClientArea;
use App\Http\Resources\Api\V1\MobileApp\getAdvertisementByItsLocationMobileApp;
use App\Http\Resources\Api\V1\MobileApp\getAllCategories;
use App\Http\Resources\Api\V1\MobileApp\getBestSellerProducts;
use App\Http\Resources\Api\V1\MobileApp\getCartFullData;
use App\Http\Resources\Api\V1\MobileApp\getCategoryProducts;
use App\Http\Resources\Api\V1\MobileApp\getCheckoutData;
use App\Http\Resources\Api\V1\MobileApp\getHotProduct;
use App\Http\Resources\Api\V1\MobileApp\getMyOrders;
use App\Http\Resources\Api\V1\MobileApp\getNewProducts;
use App\Http\Resources\Api\V1\MobileApp\getOrderDetails;
use App\Http\Resources\Api\V1\MobileApp\getProductDetails;
use App\Http\Resources\Api\V1\MobileApp\getSearchProduct;
use App\Http\Resources\Api\V1\MobileApp\getWishList;
use App\Models\Address;
use App\Models\Advertisement;
use App\Models\AdvertisementProduct;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductRatings;
use App\Models\SectionProduct;
use App\Models\User;
use App\Models\WishList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MobileAppController extends Controller
{
    public function getAdvertisements(){

//        session(['key' => 'value']);
        $advertisementProducts = AdvertisementProduct::
        with(['product.pictures', 'picture'])
            ->where('advertisement_id', 1)
            ->limit(3)
            ->where('status',1)
            ->orderByDesc('id')
            ->get();
        $results = indexMethodResource::collection($advertisementProducts)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    public function getBestSellerProducts(){
        $client_id = Auth::id();
        $sectionProducts = SectionProduct::
            with(['section','product.pictures','product.wishList'=>function($query) use($client_id){
                $query->where('client_id',$client_id);
            }])
            ->where('section_id',1)
            ->paginate(10);
//        dd(getProductRatingPerClient(30),$sectionProducts[0]->product->toArray());
        $results1 = getBestSellerProducts::collection($sectionProducts)->response()->getData();
        $results1->success = true;
        return $results1;
    }
    public function getNewProducts(){
        $client_id = Auth::id();
        $products = Product::with(['seller.country','cover_picture','wishList'=>function($query) use($client_id){
            $query->where('client_id',$client_id);
        }])->orderByDesc('created_at')->paginate(10);
        $results = getNewProducts::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getHotProduct(){
        $client_id = Auth::id();
        $today=Carbon::now()->format('Y-m-d');
        $sectionProducts = SectionProduct::
            with(['section','product.pictures'])
            ->where('section_id',3)
            ->whereDate('start_date','<=',$today)
            ->whereDate('end_date','>=',$today)
//            ->where('status',1)
            ->first();
        if($sectionProducts) {
            $results1 = new getHotProduct($sectionProducts);
            return $results1;
        }else{
            return response()->json(['message'=>'لا يوجد منتج رائج خلال هذه الفترة']);
        }
    }
    public function otherPromotions(){
        $client_id = Auth::id();
        $sectionProducts = SectionProduct::
            with(['section','product.pictures'])
            ->where('section_id',4)
            ->paginate(10);
//        dd(getProductRatingPerClient(30),$sectionProducts[0]->product->toArray());
        $results1 = getBestSellerProducts::collection($sectionProducts)->response()->getData();
        $results1->success = true;
        return $results1;
    }
    public function getAllCategories(){
        $categories = Category::with(['picture'])->whereNull('father_id')->get();
        $results = getAllCategories::collection($categories)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    public function getAllSubCategories(Category $category){
        $categories = Category::with(['picture'])->where('father_id',$category->id)->get();
        $results = getAllCategories::collection($categories)->response()->getData();
//        dd($results);
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    public function getCategoryProducts(Category $category){
        if($category->father_id){
            $client_id = Auth::id();
            $products = Product::with(['seller.country','cover_picture','wishList'=>function($query) use($client_id){
                $query->where('client_id',$client_id);
            }])->where('sub_category_id',$category->id)->paginate(10);
            $results = getCategoryProducts::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }else{
            $client_id = Auth::id();
            $sub_categories = $category->sons ? $category->sons->pluck('id') : [];
            $products = Product::with(['seller.country','cover_picture','wishList'=>function($query) use($client_id){
                $query->where('client_id',$client_id);
            }])->whereIn('sub_category_id',$sub_categories)->paginate(10);
            $results = getCategoryProducts::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }
    }
    public function searchProduct($word){
        $client_id = Auth::id();
        $products = Product::with(['seller.country','cover_picture','wishList'=>function($query) use($client_id){
            $query->where('client_id',$client_id);
        }])->where('title','like','%'.$word.'%')->paginate(10);
        $results = getSearchProduct::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getProductDetails(Product $product){
        $product->load(['category.father','seller.country','properties_values','cover_picture','pictures']);
        $results1 = new getProductDetails($product);
        return $results1;
    }
    public function addProductToCart(addProductToCartRequest $request){
        $user = Auth::user();
        $user->load('cart');
        if($user->cart){

        }else{
            $user->cart()->create([
//                'wallet_id'=>$user->current_wallet ? $user->current_wallet : null
            ]);
        }
        $user->load('cart');
        $product = Product::find($request->product_id);
//        dd($product->current_price);
        $cart_product = OrderProduct::where('order_id',$user->cart->id)->where('product_id',$request->product_id)->first();
        if($cart_product){
            $cart_product->update([
                'order_quantity'=>$request->quantity,
                'price'=>$product->current_price,
            ]);
        }else{
            $user->cart->order_products()->create([
                'product_id'=>$request->product_id,
                'order_quantity'=>$request->quantity,
                'price'=>$product->current_price,
            ]);
        }
        $user->cart->update(['order_total_price'=>((int)($user->cart->order_total_price ? $user->cart->order_total_price : 0)+($request->quantity * $product->current_price))]);
        return $this->getCartDetails();
    }
    public function getCartDetails(){
        $user = Auth::user();
        $user->load('cart');
        if($user->cart){

        }else{
            $user->cart()->create([

            ]);
        }
        $user->cart->load(['client','address','order_products.product.properties_values.property']);
        $results1 = new getCartFullData($user->cart);
        return $results1;
    }
    public function increaseCartProductQuantity(Product $product){
        $user = Auth::user();
        $user->load('cart');
//        dd($user,$user->cart);
        if($user->cart){
            $cartProduct = OrderProduct::where('product_id',$product->id)->where('order_id',$user->cart->id)->first();
            if($cartProduct) {
                $cartProduct->update([
                    'order_quantity' => ($cartProduct->order_quantity + 1)
                ]);
            }else{
                $user->cart->order_products()->create([
                    'product_id'=>$product->id,
                    'order_quantity'=>1,
                    'price'=>$product->current_price,
                ]);
            }
            $user->cart->update(['order_total_price'=>((int)($user->cart->order_total_price ? $user->cart->order_total_price : 0)+($product->current_price))]);
//            $product->update([
//                'available_quantity' => ($product->available_quantity - 1)
//            ]);
        }else{
            return response()->json(['message'=>'لا يوجد سلة حالية للمستخدم '.$user->name ,'success'=>false]);
        }

        return $this->getCartDetails();
    }
    public function decreaseCartProductQuantity(Product $product){
        $user = Auth::user();
        $user->load('cart');
//        dd($user,$user->cart);
        if($user->cart){
            $cartProduct = OrderProduct::where('product_id',$product->id)->where('order_id',$user->cart->id)->first();
            if($cartProduct->order_quantity == 1){
                $cartProduct->delete();
            }else {
                $cartProduct->update([
                    'order_quantity' => ($cartProduct->order_quantity - 1)
                ]);
            }
            $user->cart->update(['order_total_price'=>((int)($user->cart->order_total_price ? $user->cart->order_total_price : 0)-($product->current_price))]);
//            $product->update([
//                'available_quantity' => ($product->available_quantity + 1)
//            ]);
        }else{
            return response()->json(['message'=>'لا يوجد سلة حالية للمستخدم '.$user->name ,'success'=>false]);
        }

        return $this->getCartDetails();
    }
    public function deleteCartProductClientArea(Product $product){
        $user = Auth::user();
        $user->load('cart');
//        dd($user,$user->cart);
        if($user->cart){
            $cartProduct = OrderProduct::where('product_id',$product->id)->where('order_id',$user->cart->id)->first();
            if($cartProduct){
                $product->update([
                    'available_quantity' => ($product->available_quantity + $cartProduct->order_quantity)
                ]);
                $cartProduct->delete();
            }else {
                return response()->json(['message'=>'المنتج غير موجود في سلة المستخدم '.$user->name ,'success'=>false]);
            }
        }else{
            return response()->json(['message'=>'لا يوجد سلة حالية للمستخدم '.$user->name ,'success'=>false]);
        }

        return $this->getCartDetails();
    }
    public function getClientCities(){
        $user = Auth::user();
        $user->load('country.cities');
        if($user->country) {
            $cities = $user->country->cities;
            return ['data' => \App\Http\Resources\Api\V1\Cities\indexMethodResource::collection($cities), 'success' => true];
        }else{
            return response()->json(['message'=>'لا يوجد دولة للمستخدم','success'=>false]);
        }
    }
    public function addOrderDeliveryAddress(addOrderDeliveryAddressRequest $request){
        $address = Address::create($request->all());
        $user = Auth::user();
        $user->load('cart');
//        dd($user->cart->toArray(),$address->id);
        if($user->cart){
            $user->cart->update(['address_id'=>$address->id]);
        }
        return response()->json(['data'=>$address,'success'=>true]);
    }
    public function getCheckoutData(){
        $user = Auth::user();
        $user->load('cart');
        if($user->cart){

        }else{
            $user->cart()->create([

            ]);
        }
        $user->cart->load(['client','address','order_products.product.properties_values.property','promocode']);
        $results1 = new getCheckoutData($user->cart);
        return $results1;
    }
    public function checkoutToOrder(){
        $user = Auth::user();
        $user->load('cart');
        if($user->cart){
            $user->cart->load(['client','address','order_products.product.properties_values.property','promocode']);
            $user->cart->update([
                'status'=>1
            ]);
        }else{
            return response()->json(['message'=>'لا توجد سلة للمستخدم','success'=>false]);
        }
        $results1 = new getCheckoutData($user->cart);
        return $results1;
    }
    public function getMyOrders(){
        $user = Auth::user();
        $user->load('orders.client','orders.address','orders.order_products.product.properties_values.property');

        $results = getMyOrders::collection($user->orders)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    public function getOrderDetails(Order $order){
        $order->load('client','address','order_products.product.properties_values.property');

        $results = new getOrderDetails($order);
        return $results;
    }
    public function getOrderDestination(Order $order){
        if($order->status == 4 && ($order->client_id == Auth::id() || Auth::user()->type == 0)){
            return response()->json(['address'=>$order->street_address,'deliver_date_time'=>$order->updated_at,'success'=>true]);
        }else{
            return response()->json(['message'=>'لم يتم توصيل الطلبية','success'=>false]);
        }
    }
    public function submitOrderProductRating(submitOrderProductRatingRequest $request){
        $orderProduct = OrderProduct::with('product','order')->find($request->order_product_id);
        if ($orderProduct) {
            if ($orderProduct->client_id == Auth::id()) {
                $productReview = ProductRatings::where('client_id',$orderProduct->client_id)->where('product_id',$orderProduct->product_id)->first();
//                dd($productReview);
                if($productReview) {
                    $productReview->update([
                        'rating' => $request->rating,
                        'comment' => $request->comment,
                    ]);
                }else{
                    ProductRatings::create([
                        'product_id' => $orderProduct->product_id,
                        'client_id' => $orderProduct->client_id,
                        'rating' => $request->rating,
                        'comment' => $request->comment,
                    ]);
                }
                return response()->json(['message' => 'تم اضافة تقييم بنجاح', 'success' => true]);
            } else {
                return response()->json(['message' => 'الزبون لم يطلب المنتج من قبل', 'success' => false]);
            }
        }else{
            return response()->json(['message' => 'المنتج غير موجود في الطلبية', 'success' => false]);
        }
    }
    public function checkOrderProductIsRated(Product $product){
        $productReview = ProductRatings::where('client_id',Auth::id())->where('product_id',$product->id)->first();

        if($productReview) {
            return response()->json(['message' => 'تم تقييم المنتج','data'=>$productReview->rating_data, 'success' => true]);
        }else{
            return response()->json(['message' => 'لم يتم تقييم المنتج', 'success' => false]);
        }
    }
    public function getWishList(){
        $client_id = Auth::id();
        $wishlist = WishList::with(['product.seller.country','product.cover_picture'])
            ->where('client_id',$client_id)
            ->get();
        $results = getWishList::collection($wishlist)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    public function deleteWishList(Product $product){
        $user_id = Auth::id();
        $wishList = WishList::where('product_id',$product->id)->where('client_id',$user_id)->first();
        if($wishList) {
            $wishList->delete();
            return response()->json(['message' => 'تم حذف المنتج من قائمة المفضلة', 'success' => true]);
        }else{
            return response()->json(['message' => 'المنتج غير موجود في قائمة المفضلة الخاصة بالمستخدم', 'success' => false]);
        }
    }
    public function addProductToWishListMobileApp(Product $product){
        $wishlist = WishList::where('client_id',Auth::id())->where('product_id',$product->id)->first();
        if(!$wishlist){
            $product->wishList()->create(['client_id' => Auth::id()]);
            return response()->json(['message' => 'تم اضافة المنتج للمفضلة بنجاح', 'success' => true]);
        }else{
            return response()->json(['message' => 'المنتج مضاف الى المفضلة مسبقا', 'success' => false]);
        }
    }
    public function getAdvertisementByItsLocation(Advertisement $advertisement){
        $triggers = [2,3,4,5];
        if(in_array($advertisement->trigger,$triggers)) {
            $advertisementProduct = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', $advertisement->trigger)
                ->limit(1)
                ->where('status', 1)
                ->first();
            if($advertisementProduct) {
                $results = new getAdvertisementByItsLocationMobileApp($advertisementProduct);
                return $results;
            }else{
                return response()->json(['message'=>'لا يوجد منتجات في المساحة الاعلانية رقم 5','success'=>false]);
            }
        }else{
            return response()->json(['message'=>'يرجى اختيار موقع صالح للاعلان من 2 الى 5','success'=>false]);
        }
    }
    public function checkProductWishList(Product $product){
        $client_id = Auth::id();
        $wishlist = WishList::where('product_id',$product->id)->where('client_id',$client_id)->first();
        if($wishlist){
            return response()->json(['message'=>Auth::user()->name.'المنتج متواجد في المفضلة الخاصة بالزبون','success'=>true]);
        }else{
            return response()->json(['message'=>Auth::user()->name.'المنتج غير متواجد في المفضلة الخاصة بالزبون','success'=>false]);
        }
    }
    public function resetPassword(Request $request){
        $user = User::where('email',$request->email)->first();
        if($user){
            $to_name = $user->name;
            $to_email = $user->email;
            $rand = mt_rand(10000, 99999);;
            $user->update(['password_reset_code'=>$rand]);
            $data = array("name"=>$to_name, "body" =>" يرجى استخدام الرمز ".$user->password_reset_code." لاستعادة كلمة المرور.");
                Mail::send("emails.resetPassword", $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                        ->subject("استعادة كلمة المرور");
                $message->from("support@jomltna.com","supportMailer");
                });
            return response()->json(['message'=>' تم ارسال بريد الكتروني بتفاصيل اعادة كلمة المرور للبريد ' .$to_email. ' بنجاح ','success'=>true]);
        }else{
            return response()->json(['message'=>' البريد الالكتروني ' .$request->email. ' غير صحيح ','success'=>false]);
        }
    }
    public function checkPasswordResetCode($code){
        $user = User::where('password_reset_code',$code)->first();
        if($user){
//            dd(Carbon::now()->diffInMinutes(Carbon::parse($user->updated_at)));
            if(Carbon::now()->diffInMinutes(Carbon::parse($user->updated_at)) > 5){
                $user->update(['password_reset_code'=>0]);
                return response()->json(['message'=>'رمز استرداد كلمة المرور منتهي الصلاحية يرجى اعادة طلب رمز آخر','success'=>false]);
            }else{
                $user->update(['password_reset_code'=>1]);
                return response()->json(['message'=>'رمز استرداد كلمة المرور صحيح','success'=>true]);
            }
        }else{
            $user->update(['password_reset_code'=>0]);
            return response()->json(['message'=>'رمز استرداد كلمة المرور خطأ','success'=>false]);
        }
    }
    public function updateUserPassword(Request $request){
        $user = User::where('email',$request->email)->first();
        if($user){
            if($user->password_reset_code){
                if(Carbon::now()->diffInMinutes(Carbon::parse($user->updated_at)) > 5){
                    $user->update(['password_reset_code'=>null]);
                    return response()->json(['message'=>'قمت بتفعيل رمز استرداد كلمة المرور منذ وقت كبير','success'=>false]);
                }else{
                    $user->update([
                        'password'=>Hash::make($request->password),
                        'password_reset_code'=>2
                    ]);
                    return response()->json(['message'=>'تم تعديل كلمة المرور بنجاح','success'=>true]);
                }
            }else{
                $user->update(['password_reset_code'=>null]);
                return response()->json(['message'=>'لم تقم بطلب استرداد كلمة المرور وتأكيد رمز تفعيل الاسترداد او ادخلت رمز تفعيل استرداد كلمة المرور منتهي الصلاحية','success'=>false]);
            }
        }else{
            $user->update(['password_reset_code'=>null]);
            return response()->json(['message'=>' البريد الالكتروني ' .$request->email. ' غير صحيح ','success'=>false]);
        }
    }
}
