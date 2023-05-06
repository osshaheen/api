<?php

namespace App\Http\Controllers\ClientAreaApi\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\updateUserProfileRequest;
use App\Http\Requests\V1\ClientArea\addOrderBillingAddress;
use App\Http\Requests\V1\ClientArea\filterProductsByCategoryOrSubCategoryRequest;
use App\Http\Requests\V1\ClientArea\newsLetterSubscriptionRequest;
use App\Http\Requests\V1\ClientArea\addProductToCartRequest;
use App\Http\Resources\Api\V1\MobileApp\filterProductsByCategory;
use App\Http\Resources\Api\V1\MobileApp\getAdvertisementByItsLocationClientArea;
use App\Http\Resources\Api\V1\MobileApp\getAdvertisementsClientArea;
use App\Http\Resources\Api\V1\MobileApp\getAllCategories;
use App\Http\Resources\Api\V1\MobileApp\getAllCountriesClientArea;
use App\Http\Resources\Api\V1\MobileApp\getAllNestedCategoriesClientArea;
use App\Http\Resources\Api\V1\MobileApp\getAllTodayAvailableCouponsClientArea;
use App\Http\Resources\Api\V1\MobileApp\getBestSellerProductsClientArea;
use App\Http\Resources\Api\V1\MobileApp\getCartFullData;
use App\Http\Resources\Api\V1\MobileApp\getCheckoutData;
use App\Http\Resources\Api\V1\MobileApp\getClientOrdersClientArea;
use App\Http\Resources\Api\V1\MobileApp\getClientWishList;
use App\Http\Resources\Api\V1\MobileApp\getHotProductClientArea;
use App\Http\Resources\Api\V1\MobileApp\getNewProductsClientArea;
use App\Http\Resources\Api\V1\MobileApp\getSearchProductClientArea;
use App\Http\Resources\Api\V1\MobileApp\showProductDetails;
use App\Http\Resources\Api\V1\newsletter\storeMethodResource;
use App\Models\Address;
use App\Models\Advertisement;
use App\Models\AdvertisementProduct;
use App\Models\BillingAddress;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\NewsLetter;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\SectionProduct;
use App\Models\WishList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAreaController extends Controller
{

    public function getAllCategories(){
        $categories = Category::with(['picture'])->whereNull('father_id')->get();
        $results = getAllCategories::collection($categories)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    public function getAllNestedCategories(){
        $categories = Category::with(['picture','sons.picture'])->whereNull('father_id')->get();
        $results = getAllNestedCategoriesClientArea::collection($categories)->response()->getData();
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
    public function getAllCountries(){
        return ['data'=>getAllCountriesClientArea::collection(Country::all()),'success'=>true];
    }
    public function getAdvertisements(){

//        session(['key' => 'value']);
        $advertisementProducts = AdvertisementProduct::
        with(['product.pictures', 'picture'])
            ->where('advertisement_id', 1)
            ->limit(3)
            ->where('status',1)
            ->orderByDesc('id')
            ->get();
        $results = getAdvertisementsClientArea::collection($advertisementProducts)->response()->getData();
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
        $results1 = getBestSellerProductsClientArea::collection($sectionProducts)->response()->getData();
        $results1->success = true;
        return $results1;
    }

    public function getNewProducts(){
        $client_id = Auth::id();
        $products = Product::with(['seller.country','cover_picture','wishList'=>function($query) use($client_id){
            $query->where('client_id',$client_id);
        }])->orderByDesc('created_at')->paginate(10);
        $results = getNewProductsClientArea::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getHotProduct(){
        $client_id = Auth::id();
        $sectionProducts = SectionProduct::
        with(['section','product.pictures'])
            ->where('section_id',3)
            ->where('status',1)
            ->first();
//        dd(getProductRatingPerClient(30),$sectionProducts[0]->product->toArray());
        if($sectionProducts) {
            $results1 = new getHotProductClientArea($sectionProducts);
            return $results1;
        }else{
            return response()->json(['message'=>'لا يوجد منتجات في قائمة المنتجات الرائجة في بلدك','success'=>true]);
        }
    }
    public function newsLetterSubscription(newsLetterSubscriptionRequest $request){
        $newsLetter = NewsLetter::create($request->all());
        return new storeMethodResource($newsLetter);
    }

    public function searchProduct($word){
        $client_id = Auth::id();
        $category = Category::where('name','like','%'.$word.'%')->first();
//        dd($category->toArray());
        if($category){
            if($category->father_id){
                $products = Product::with(['category.father','seller.country','one_cover_picture','wishList'=>function($query) use($client_id){
                    $query->where('client_id',$client_id);
                }])
                    ->where('sub_category_id',$category->id)
                    ->paginate(10);
            }else{
                $categories_id_list = getSubCategoriesIdList($category->id);
                $products = Product::with(['category.father','seller.country','one_cover_picture','wishList'=>function($query) use($client_id){
                    $query->where('client_id',$client_id);
                }])
                    ->whereIn('sub_category_id',$categories_id_list)
                    ->paginate(10);
            }
            $results = getSearchProductClientArea::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }else{
            $products = Product::with(['category.father','seller.country','one_cover_picture','wishList'=>function($query) use($client_id){
                $query->where('client_id',$client_id);
            }])
                ->where('title','like','%'.$word.'%')
                ->paginate(10);
            $results = getSearchProductClientArea::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }
    }
    public function getAllTodayAvailableCoupons(){
        $today = Carbon::now()->format('Y-m-d');
        $coupons = PromoCode::
        where('start_date','<=',$today)
            ->where('end_date','>=',$today)
            ->limit(1)
            ->withCount(['orders'=>function($query){
                $query->where('status',4);
            }])->paginate(10);
        $results = getAllTodayAvailableCouponsClientArea::collection($coupons)->response()->getData();
        $results->success = true;
        return $results;//->additional(['success'=>true]);

    }
    public function countrySearch($word){
        $countries = Country::where('name','like','%'.$word.'%')->get();
        return ['data'=>getAllCountriesClientArea::collection($countries),'success'=>true];
    }

    public function filterProductsByCategory(Category $category){
        $client_id = Auth::id();
        $categories_id_list = getSubCategoriesIdList($category->id);
        $products = Product::with(['category.father','seller.country','one_cover_picture','wishList'=>function($query) use($client_id){
            $query->where('client_id',$client_id);
        }])
            ->whereIn('sub_category_id',$categories_id_list)
            ->paginate(10);
        $results = filterProductsByCategory::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function filterProductsBySubCategory(Category $category)
    {
        $client_id = Auth::id();
        if ($category) {
            if ($category->father_id) {
                $products = Product::with(['category.father', 'seller.country', 'one_cover_picture', 'wishList' => function ($query) use ($client_id) {
                    $query->where('client_id', $client_id);
                }])
                    ->where('sub_category_id', $category->id)
                    ->paginate(10);
            } else {
                $categories_id_list = getSubCategoriesIdList($category->id);
                $products = Product::with(['category.father', 'seller.country', 'one_cover_picture', 'wishList' => function ($query) use ($client_id) {
                    $query->where('client_id', $client_id);
                }])
                    ->whereIn('sub_category_id', $categories_id_list)
                    ->paginate(10);
            }
            $results = filterProductsByCategory::collection($products)->response()->getData();
            $results->success = true;
            return $results;
        }else{
            return response()->json(['message'=>'التصنيف غير موجود','success'=>false]);
        }
    }
    public function filterProductsByCategoryOrSubCategory(filterProductsByCategoryOrSubCategoryRequest $request)
    {
        $categories_id_list = getAllPossibleSubCategoriesId($request->categoryIdList);
        $client_id = Auth::id();
        $products = Product::with(['category.father', 'seller.country', 'one_cover_picture', 'wishList' => function ($query) use ($client_id) {
            $query->where('client_id', $client_id);
        }])
            ->whereIn('sub_category_id', $categories_id_list)
            ->paginate(10);
        $results = filterProductsByCategory::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getProductInsidePriceRange($first_price,$second_price)
    {
        $client_id = Auth::id();

        $products = Product::with(['category.father', 'seller.country', 'one_cover_picture', 'wishList' => function ($query) use ($client_id) {
            $query->where('client_id', $client_id);
        }])
            ->where('price','>=',$first_price)
            ->where('price','<=',$second_price)
            ->paginate(10);
        $results = filterProductsByCategory::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getAdvertisementByLocation(Advertisement $advertisement){
        if ($advertisement->trigger == 2){
            $advertisementProduct1 = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', 2)
                ->limit(1)
                ->where('status',1)
                ->orderByDesc('id')
                ->get();
            $advertisementProduct2 = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', 3)
                ->limit(1)
                ->where('status',1)
                ->orderByDesc('id')
                ->get();
            $advertisementProducts = $advertisementProduct1->merge($advertisementProduct2);
//            dd($advertisementProducts->toArray());
        }elseif ($advertisement->trigger == 3){
            $advertisementProduct1 = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', 4)
                ->limit(1)
                ->where('status',1)
                ->orderByDesc('id')
                ->get();
            $advertisementProduct2 = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', 5)
                ->limit(1)
                ->where('status',1)
                ->orderByDesc('id')
                ->get();
            $advertisementProducts = $advertisementProduct1->merge($advertisementProduct2);
//            dd($advertisementProducts->toArray());
        }else{
            return response()->json(['message'=>'لا يوجد منتجات','success'=>false]);
        }
        $results = getAdvertisementsClientArea::collection($advertisementProducts)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;

    }

    public function getUserProfile(){
//        dd(Auth::user());
        return Auth::user()->profile;
    }
    public function updateUserProfile(updateUserProfileRequest $request){
        $user = Auth::user();
        $data = $request->only('name', 'email', 'address','country_id');
        if(isset($request->vendor_wallet_account)){
            $data['vendor_wallet_account'] = $request->vendor_wallet_account;
        }
        if(isset($request->vendor_bank_account)){
            $data['vendor_bank_account'] = $request->vendor_bank_account;
        }
        if($user->type == 1 || $user->type == 2){
            if(isset($request->password) && !empty($request->password)){
                $data['password'] = Hash::make($request->password);
                if(isset($request->role) && !empty($request->role)){
                    $data['role'] = $request->role;
                    if(isset($request->mobile) && !empty($request->mobile)){
                        $data['mobile'] = $request->mobile;
                        $user->update($data);
                    }else{
                        $user->update($data);
                    }
                }else{
                    if(isset($request->mobile) && !empty($request->mobile)){
                        $data['mobile'] = $request->mobile;
                        $user->update($data);
                    }else{
                        $user->update($data);
                    }
                }
            }else{
                if(isset($request->role) && !empty($request->role)){
                    $data['role'] = $request->role;
                    if(isset($request->mobile) && !empty($request->mobile)){
                        $data['mobile'] = $request->mobile;
                        $user->update($data);
                    }else{
                        $user->update($data);
                    }
                }else{
                    if(isset($request->mobile) && !empty($request->mobile)){
                        $data['mobile'] = $request->mobile;
                        $user->update($data);
                    }else{
                        $user->update($data);
                    }
                }
            }
        }else{
            if(isset($request->password) && !empty($request->password)){
                $data['password'] = Hash::make($request->password);
                if(isset($request->mobile) && !empty($request->mobile)){
                    $data['mobile'] = $request->mobile;
                    $user->update($data);
                }else{
                    $user->update($data);
                }
            }else{
                if(isset($request->mobile) && !empty($request->mobile)){
                    $data['mobile'] = $request->mobile;
                    $user->update($data);
                }else{
                    $user->update($data);
                }
            }
        }
        if($request->file('asset_file')){
            $old_name = $request->file('asset_file')->getClientOriginalName();
            $extension = $request->file('asset_file')->getClientOriginalExtension();
            $file_name = $request->file('asset_file')->store('public','public');
//            dd($old_name,$extension);
            $media_trigger = $user->type == 1 ? 1 : ($user->type == 2 ? 2 : 0);
            if($media_trigger == 1){
                $user->sellerLogo()->delete();
            }elseif ($media_trigger == 2){
                $user->buyerPicture()->delete();
            }elseif ($media_trigger == 0){
                $user->adminPicture()->delete();
            }
            $user->media()->create([
                'name'=>$file_name,
                'old_name'=>$old_name,
                'type'=>$extension,
                'media_trigger'=>$media_trigger,
                'is_cover'=>0
            ]);
        }
        return $user->profile;
    }
    public function getClientOrders(){
        $user = Auth::user();
        $orders = Order::with(['client','order_products.product.seller','order_products.product.pictures','address.city','billingAddress.city','promocode','wallet'])
            ->where('status','>',0)->whereNotNull('status')
            ->where('client_id',$user->id)
            ->paginate(10);
        $results = getClientOrdersClientArea::collection($orders)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function getClientWishList(){
        $client_id = Auth::id();
        $products = WishList::with(['product.seller.country','product.cover_picture'])->where('client_id',$client_id)->paginate(10);
        $results = getClientWishList::collection($products)->response()->getData();
        $results->success = true;
        return $results;
    }
    public function checkCouponValidity($coupon_title){
        $today = Carbon::now()->format('Y-m-d');
        $coupon = PromoCode::
        where('title',$coupon_title)
            ->first();
        if($coupon){
            if(($coupon->start_date <= $today) && ($coupon->end_date >= $today)) {
                $user = Auth::user();
                if ($user->cart) {
                    $user->cart->update([
                        'promocode_id' => $coupon->id
                    ]);
                }
                return $this->getCartDetails();
            }else{
                return response()->json(['message'=>'الكوبون منتهي الصلاحية','success'=>false]);
            }
        }else{
            return response()->json(['message'=>'الكوبون غير موجود','success'=>false]);
        }

    }
    public function getCityShippingCost(City $city){
        $city->load('country');
        return response()->json(['data'=>[
            'delivery_cost'=>$city->delivery_cost,
            'currency'=>$city->currency,
            'currency_abbreviation'=>$city->currency_abbreviation
        ],'success'=>true]);
    }
    public function addOrderBillingAddress(addOrderBillingAddress $request){
        $user = Auth::user();
        if($user->cart){
            $billing_address = BillingAddress::create($request->all());
            $address = Address::create($request->all());
            $user->cart->update([
                'address_id'=>$address->id,
                'billing_details_id'=>$billing_address->id,
                'payment_method'=>$request->payment_method,
                'status'=>1
            ]);
            return new \App\Http\Resources\Api\V1\MobileApp\addOrderBillingAddress($billing_address);
        }else{
            return response()->json(['message'=>'لا يوجد منتجات في سلة المستخدم']);
        }
    }
    public function setOrderPaymentMethod($payment_method,Order $order){
        if($order->client_id == Auth::id()){
            $order->update(['payment_method'=>$payment_method]);
            return response()->json(['message'=>'تم تغيير طريقة الدفع للطلبية بنجاح','success'=>true]);
        }else{
            return response()->json(['message'=>'الطلبية تتبع لزبون آخر','success'=>false]);
        }

    }
    public function showProductDetails(Product $product){
        $client_id = Auth::id();
        $product->load(['category.father','seller.country',
            'properties_values','pictures','cover_picture',
            'wishList'=>function($query) use($client_id){
                $query->where('client_id',$client_id);
            }])->first();
//        dd($product->toArray());
        $results = new showProductDetails($product);
        return $results;
    }
    public function addProductToWishListClientArea(Product $product){
        $wishlist = WishList::where('client_id',Auth::id())->where('product_id',$product->id)->first();
        if(!$wishlist) {
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
                $results = new getAdvertisementByItsLocationClientArea($advertisementProduct);
                return $results;
            }else{
                return response()->json(['message'=>'لا يوجد منتجات في المساحة الاعلانية رقم 5','success'=>false]);
            }
        }else{
            return response()->json(['message'=>'يرجى اختيار موقع صالح للاعلان من 2 الى 5','success'=>false]);
        }
    }
    public function getHighestLeastPrices(Country $country){
        $highest = Product::orderBy('price','desc')->first();
        $least = Product::orderBy('price','asc')->first();
        return response()->json([
            'data'=>['highest'=>($highest ? $highest->price : 0),'least'=>($least ? $least->price : 0)],
            'currency'=>$country->currency,
            'currency_abbreviation'=>$country->currency_abbreviation,
            'success'=>true
        ]);
    }
    public function getAllApprovedProducts(){
        $client_id = Auth::id();
        $products = Product::with(['category.father','seller.country','one_cover_picture','wishList'=>function($query) use($client_id){
            $query->where('client_id',$client_id);
        }])
            ->where('is_approved',1)
            ->paginate(10);
        $results = filterProductsByCategory::collection($products)->response()->getData();
        $results->success = true;
        return $results;
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
        return $this->getCartDetails();
    }

    public function getCartDetails(){
        $user = Auth::user();
//        dd($user);
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
//            $product->update([
//                'available_quantity' => ($product->available_quantity + 1)
//            ]);
        }else{
            return response()->json(['message'=>'لا يوجد سلة حالية للمستخدم '.$user->name ,'success'=>false]);
        }

        return $this->getCartDetails();
    }
    public function deleteCartProduct(Product $product){
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
    public function getOrdersStatuses(){
        return response()->json([
            'data'=>[
                1=>'جاري الموافقه على الطلب',
                2=>'جاري الشحن',
                3=>'في الطريق',
                4=>'تم التوصيل',
                5=>'مرفوض'
            ],
            'success'=>true
        ]);
    }
}
