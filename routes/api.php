<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('paytabs',function(Request $request){
    dd($request);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register',[\App\Http\Controllers\Api\V1\RegisterController::class,'register']);
Route::post('login',[\App\Http\Controllers\Api\V1\RegisterController::class,'login']);
Route::get('countriesList',[\App\Http\Controllers\Api\V1\CountriesController::class,'index']);
Route::get('citiesList/{country}',[\App\Http\Controllers\Api\V1\CitiesController::class,'index']);

//Route::get('/foo', function () {
//    dd(\Illuminate\Support\Facades\Hash::make('0123456.Admin*'));
//    \Illuminate\Support\Facades\Artisan::call('storage:link');
//});

// mobile app public apis
Route::get('getAdvertisements',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getAdvertisements']);
Route::get('getBestSellerProducts',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getBestSellerProducts']);
Route::get('getNewProducts',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getNewProducts']);
Route::get('getHotProduct',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getHotProduct']);
Route::get('otherPromotions',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'otherPromotions']);
Route::get('getAllCategories',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getAllCategories']);
Route::get('getAllSubCategories/{category}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getAllSubCategories']);
Route::get('getCategoryProducts/{category}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getCategoryProducts']);
Route::get('searchProduct/{word}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'searchProduct']);
Route::get('getProductDetails/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getProductDetails']);
Route::post('mobileAppRegister',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'register']);
Route::post('mobileAppLogin',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'login']);
Route::get('mobileAppCountriesList',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'countriesList']);
Route::get('mobileAppCitiesList/{country}',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'citiesList']);
Route::get('getAdvertisementByItsLocationMobileApp/{advertisement}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getAdvertisementByItsLocation']);


// Client Area public apis
Route::get('getAllCategoriesClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAllCategories']);
Route::get('getAllNestedCategoriesClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAllNestedCategories']);
Route::get('getAllSubCategoriesClientArea/{category}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAllSubCategories']);
Route::get('getAllCountriesClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAllCountries']);
Route::get('getAdvertisementsClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAdvertisements']);
Route::get('getBestSellerProductsClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getBestSellerProducts']);
Route::get('getNewProductsClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getNewProducts']);
Route::get('getHotProductClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getHotProduct']);
Route::post('newsLetterSubscription',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'newsLetterSubscription']);
Route::get('searchProductClientArea/{word}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'searchProduct']);
Route::get('countrySearchClientArea/{word}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'countrySearch']);
Route::get('getAllTodayAvailableCouponsClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAllTodayAvailableCoupons']);
Route::post('filterProductsByCategoryOrSubCategory',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'filterProductsByCategoryOrSubCategory']);
Route::get('filterProductsByCategoryClientArea/{category}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'filterProductsByCategory']);
Route::get('filterProductsBySubCategoryClientArea/{category}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'filterProductsBySubCategory']);
Route::get('getAdvertisementByLocationClientArea/{advertisement}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAdvertisementByLocation']);
Route::get('getAdvertisementByItsLocationClientArea/{advertisement}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAdvertisementByItsLocation']);
Route::get('getProductInsidePriceRangeClientArea/{first_price}/{second_price}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getProductInsidePriceRange']);
Route::get('getCityShippingCostClientArea/{city}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getCityShippingCost']);
Route::get('getHighestLeastPrices/{country}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getHighestLeastPrices']);
Route::get('getAllApprovedProductsClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getAllApprovedProducts']);
Route::get('getOrdersStatuses',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getOrdersStatuses']);

Route::post('resetPassword',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'resetPassword']);
Route::get('checkPasswordResetCode/{code}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'checkPasswordResetCode']);
Route::post('updateUserPassword',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'updateUserPassword']);


Route::middleware('auth:sanctum')->group(function(){

    Route::get('mobileAppLogout',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'logout']);
    Route::get('mobileAppGetProfile',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'getUserProfile']);
    Route::put('mobileAppUpdateUserProfile',[\App\Http\Controllers\MobileAppApi\V1\RegisterController::class,'updateUserProfile']);



    Route::get('logout',[\App\Http\Controllers\Api\V1\RegisterController::class,'logout']);
    Route::get('getProfile',[\App\Http\Controllers\Api\V1\RegisterController::class,'getUserProfile']);
    Route::put('updateUserProfile',[\App\Http\Controllers\Api\V1\RegisterController::class,'updateUserProfile']);

    Route::resource('newsletters',\App\Http\Controllers\Api\V1\NewsLetterController::class);

    Route::resource('countries',\App\Http\Controllers\Api\V1\CountriesController::class);
    Route::resource('cities',\App\Http\Controllers\Api\V1\CitiesController::class)->except('index');

    Route::resource('billingAddresses',\App\Http\Controllers\Api\V1\BillingAddressController::class);

    Route::resource('categories',\App\Http\Controllers\Api\V1\CategoriesController::class)->except('destroy');
    Route::get('categoriesSelect',[\App\Http\Controllers\Api\V1\CategoriesController::class,'categoriesSelect']);
    Route::get('subCategoriesSelect/{category}',[\App\Http\Controllers\Api\V1\CategoriesController::class,'subCategoriesSelect']);
    Route::get('getSubCategories/{category}',[\App\Http\Controllers\Api\V1\CategoriesController::class,'getSubCategories']);
    Route::delete('destroyCategories/{category}',[\App\Http\Controllers\Api\V1\CategoriesController::class,'destroy']);
    Route::get('getCategoryRelatedProducts/{category}',[\App\Http\Controllers\Api\V1\CategoriesController::class,'getCategoryRelatedProducts'])->name('categories.getCategoryRelatedProducts');

    Route::resource('category_properties',\App\Http\Controllers\Api\V1\CategoryPropertiesController::class)->except('index','destroy');
    Route::get('categoryPropertiesList/{category}',[\App\Http\Controllers\Api\V1\CategoryPropertiesController::class,'index']);
    Route::delete('destroyCategoryProperties/{categoryProperty}',[\App\Http\Controllers\Api\V1\CategoryPropertiesController::class,'destroy']);

    Route::resource('products',\App\Http\Controllers\Api\V1\ProductsController::class);
    Route::post('approveProduct',[\App\Http\Controllers\Api\V1\ProductsController::class,'approveProduct']);
    Route::post('rejectProduct',[\App\Http\Controllers\Api\V1\ProductsController::class,'rejectProduct']);
    Route::get('productPropertiesName/{category}',[\App\Http\Controllers\Api\V1\ProductsController::class,'productPropertiesName']);
    Route::get('getAllUnApprovedProducts',[\App\Http\Controllers\Api\V1\ProductsController::class,'getAllUnApprovedProducts']);
    Route::get('getAllApprovedProducts',[\App\Http\Controllers\Api\V1\ProductsController::class,'getAllApprovedProducts']);
    Route::get('getAllRejectedProducts',[\App\Http\Controllers\Api\V1\ProductsController::class,'getAllRejectedProducts']);
    Route::get('getAllCategoryFilteredProducts/{category}',[\App\Http\Controllers\Api\V1\ProductsController::class,'getAllCategoryFilteredProducts']);

    Route::resource('categoryPropertyValues',\App\Http\Controllers\Api\V1\CategoryPropertyValuesController::class)->except('index');
    Route::get('productPropertyValues/{category_id}',[\App\Http\Controllers\Api\V1\CategoryPropertyValuesController::class,'index']);

    Route::resource('orders',\App\Http\Controllers\Api\V1\OrdersController::class);
    Route::get('getIsRejectedOrders',[\App\Http\Controllers\Api\V1\OrdersController::class,'getIsRejectedOrders']);
    Route::get('getApprovalPendingOrders',[\App\Http\Controllers\Api\V1\OrdersController::class,'getApprovalPendingOrders']);
    Route::get('getIsShippingOrders',[\App\Http\Controllers\Api\V1\OrdersController::class,'getIsShippingOrders']);
    Route::get('getInRoadOrders',[\App\Http\Controllers\Api\V1\OrdersController::class,'getInRoadOrders']);
    Route::get('getDeliveredOrders',[\App\Http\Controllers\Api\V1\OrdersController::class,'getDeliveredOrders']);
    Route::post('setOrderIsShippingStatus',[\App\Http\Controllers\Api\V1\OrdersController::class,'setOrderIsShippingStatus']);
    Route::post('setOrderInRoadStatus',[\App\Http\Controllers\Api\V1\OrdersController::class,'setOrderInRoadStatus']);
    Route::post('setOrderDeliveredStatus',[\App\Http\Controllers\Api\V1\OrdersController::class,'setOrderDeliveredStatus']);
    Route::post('setOrderApprovalPendingStatus',[\App\Http\Controllers\Api\V1\OrdersController::class,'setOrderApprovalPendingStatus']);
    Route::post('setOrderRejectedStatus',[\App\Http\Controllers\Api\V1\OrdersController::class,'setOrderRejectedStatus']);

    Route::get('getRevenuesSum',[\App\Http\Controllers\Api\V1\RevenuesController::class,'getRevenuesSum']);
    Route::get('getRevenuesDetails',[\App\Http\Controllers\Api\V1\RevenuesController::class,'getRevenuesDetails']);
    Route::get('getVendorsRevenuesDetails',[\App\Http\Controllers\Api\V1\RevenuesController::class,'getVendorsRevenuesDetails']);

    Route::resource('coupons',\App\Http\Controllers\Api\V1\CouponsController::class);
//    Route::post('sectionProducts',function (Request $request){dd($request->all());});
    Route::resource('sectionProducts',\App\Http\Controllers\Api\V1\SectionProductsController::class)->except('index');
    Route::get('get_section_products/{section_id}',[\App\Http\Controllers\Api\V1\SectionProductsController::class,'index']);
    Route::get('getAllSections',[\App\Http\Controllers\Api\V1\SectionProductsController::class,'getAllSections']);

    Route::resource('advertisementProducts',\App\Http\Controllers\Api\V1\AdvertisementController::class)->except('index');
    Route::get('get_advertisement_products/{location}',[\App\Http\Controllers\Api\V1\AdvertisementController::class,'index']);
    Route::get('getAllCurrentAdds',[\App\Http\Controllers\Api\V1\AdvertisementController::class,'getAllCurrentAdds']);

    Route::resource('users',\App\Http\Controllers\Api\V1\UsersController::class)->except('index');
    Route::get('usersList/{type}',[\App\Http\Controllers\Api\V1\UsersController::class,'index']);
    Route::post('closeVendorWallet',[\App\Http\Controllers\Api\V1\UsersController::class,'closeVendorWallet']);
    Route::get('blockUser/{user}',[\App\Http\Controllers\Api\V1\RegisterController::class,'blockUser']);
    Route::get('unBlockUser/{user}',[\App\Http\Controllers\Api\V1\RegisterController::class,'unBlockUser']);


    // mobile app
    Route::post('addProductToCart',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'addProductToCart']);
    Route::get('getCartDetails',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getCartDetails']);
    Route::get('increaseCartProductQuantity/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'increaseCartProductQuantity']);
    Route::get('decreaseCartProductQuantity/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'decreaseCartProductQuantity']);
    Route::get('deleteCartProduct/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'deleteCartProductClientArea']);
    Route::get('getClientCities',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getClientCities']);
    Route::post('addOrderDeliveryAddress',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'addOrderDeliveryAddress']);
    Route::get('getCheckoutData',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getCheckoutData']);
    Route::get('getMyOrders',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getMyOrders']);
    Route::get('getOrderDetails/{order}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getOrderDetails']);
    Route::get('getOrderDestination/{order}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getOrderDestination']);
    Route::post('submitOrderProductRating',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'submitOrderProductRating']);
    Route::get('getWishListMobileApp',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getWishList']);
    Route::get('deleteWishList/{wishList}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'deleteWishList']);
    Route::get('addProductToWishListMobileApp/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'addProductToWishListMobileApp']);
    Route::get('checkProductWishList/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'checkProductWishList']);
    Route::get('checkoutToOrder',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'checkoutToOrder']);
    Route::get('checkOrderProductIsRated/{product}',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'checkOrderProductIsRated']);

    // client area
    Route::get('getClientWishListClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getClientWishList']);
    Route::get('getClientOrdersClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getClientOrders']);
    Route::get('getUserProfileClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getUserProfile']);
    Route::put('updateUserProfileClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'updateUserProfile']);
    Route::get('setOrderPaymentMethodClientArea/{method}/{order}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'setOrderPaymentMethod']);
    Route::get('addProductToWishListClientArea/{product}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'addProductToWishListClientArea']);
    Route::post('addOrderBillingAddressClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'addOrderBillingAddress']);
    Route::get('setSessionCountry/{country}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'setSessionCountry']);

    Route::post('addProductToCartClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'addProductToCart']);
    Route::get('getCartDetailsClientArea',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'getCartDetails']);
    Route::get('increaseCartProductQuantityClientArea/{product}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'increaseCartProductQuantity']);
    Route::get('decreaseCartProductQuantityClientArea/{product}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'decreaseCartProductQuantity']);
    Route::get('deleteCartProductClientArea/{product}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'deleteCartProduct']);
    Route::get('checkCouponValidityClientArea/{coupon_title}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'checkCouponValidity']);

    Route::get('getCheckoutDataClientArea',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'getCheckoutData']);
    Route::get('checkoutToOrderClientArea',[\App\Http\Controllers\MobileAppApi\V1\MobileAppController::class,'checkoutToOrder']);

    Route::get('showProductDetailsClientArea/{product}',[\App\Http\Controllers\ClientAreaApi\V1\ClientAreaController::class,'showProductDetails']);


    Route::get('statistics',[\App\Http\Controllers\Api\V1\StatisticsController::class,'statistics']);
    Route::get('getRevenuesReportPerYear/{year}',[\App\Http\Controllers\Api\V1\StatisticsController::class,'getRevenuesReportPerYear']);

});
