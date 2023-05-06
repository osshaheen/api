<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\advertisementProducts\newAdvertisementProductRequest;
use App\Http\Requests\V1\advertisementProducts\updateAdvertisementProductRequest;
use App\Http\Resources\Api\V1\advertisementProducts\indexMethodResource;
use App\Http\Resources\Api\V1\advertisementProducts\showMethodResource;
use App\Http\Resources\Api\V1\advertisementProducts\storeMethodResource;
use App\Http\Resources\Api\V1\advertisementProducts\updateMethodResource;
use App\Models\AdvertisementProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($location)
    {
        if($location==1) {
            $advertisementProducts = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', $location)
                ->limit(3)
                ->where('status',1)
                ->orderByDesc('id')
                ->get();
            $results = indexMethodResource::collection($advertisementProducts)->response()->getData();
        }else{
            $advertisementProducts = AdvertisementProduct::
            with(['product.pictures', 'picture'])
                ->where('advertisement_id', $location)
                ->limit(1)
                ->where('status',1)
                ->orderByDesc('id')
                ->first();
            $results = new indexMethodResource($advertisementProducts);
        }
        $results1['data'] = $results;
        $results1['success'] = true;
//        dd($advertisementProducts);
        return $results1;//->additional(['success'=>true]);
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
    public function store(newAdvertisementProductRequest $request)
    {
//        dd($request);
        $data = $request->all();
        if ($request->location == 1){
            $AdvertisementProducts = AdvertisementProduct::where('advertisement_id',1)
                ->where('status',1)
                ->get();
            if($AdvertisementProducts->count() > 3){
                $AdvertisementProductIds = AdvertisementProduct::where('advertisement_id',1)
                    ->where('status',1)
                    ->orderByDesc('id')
                    ->limit(3)
                    ->get()->pluck('id');
                AdvertisementProduct::where('advertisement_id',1)
                    ->where('status',1)
                    ->orderByDesc('id')
                    ->whereNotIn('id',$AdvertisementProductIds)
                    ->update(['status' => 0]);
            }else{
                $AdvertisementProduct = AdvertisementProduct::where('advertisement_id',1)
                    ->where('status',1)
                    ->orderByDesc('id')->first();
                if($AdvertisementProduct) {
                    $AdvertisementProduct->update(['status' => 0]);
                }
            }
        }else{
            AdvertisementProduct::where('advertisement_id',$request->location)
                ->update(['status'=>0]);
        }
        $data['advertisement_id'] = $request->location;
        $advertisementProduct = AdvertisementProduct::create($data);
        if($request->file('image')){
            $old_name = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name = $request->file('image')->store('public','public');
//            dd($old_name,$extension);
            $advertisementProduct->picture()->create([
                'name'=>$file_name,
                'old_name'=>$old_name,
                'type'=>$extension,
                'media_trigger'=>5,
                'is_cover'=>0
            ]);
        }
        $advertisementProduct->load(['picture','product.pictures']);
        return new storeMethodResource($advertisementProduct);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $advertisementProduct = AdvertisementProduct::find($id);
        if($advertisementProduct){
            $advertisementProduct->load(['picture','product.pictures']);
//            dd(Auth::user());
            return new showMethodResource($advertisementProduct);
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
    public function update(updateAdvertisementProductRequest $request,AdvertisementProduct $advertisementProduct)
    {
        $data = $request->all();
        if($request->status == 1) {
            if ($request->location == 1) {
                $AdvertisementProduct = AdvertisementProduct::where('advertisement_id', 1)
                    ->orderByDesc('id')
                    ->first();
                if ($AdvertisementProduct) {
                    $AdvertisementProduct->update(['status' => 0]);
                }
            } else {
                AdvertisementProduct::where('advertisement_id', $request->location)
                    ->update(['status' => 0]);
            }
        }
        $data['advertisement_id'] = $request->location;
        $advertisementProduct->update($data);
        if($request->file('image')){
            $old_name = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name = $request->file('image')->store('public','public');
            $advertisementProduct->picture()->delete();
            $advertisementProduct->picture()->create([
                'name'=>$file_name,
                'old_name'=>$old_name,
                'type'=>$extension,
                'media_trigger'=>5,
                'is_cover'=>0
            ]);
        }
        $advertisementProduct->load(['picture','product.pictures']);
        return new updateMethodResource($advertisementProduct);
    }
    public function getAllCurrentAdds(){
        $adds1 = AdvertisementProduct::with(['product.pictures', 'picture'])->where('advertisement_id',1)
            ->limit(3)
            ->orderByDesc('id')
            ->get();
        $adds2 = AdvertisementProduct::with(['product.pictures', 'picture'])->where('advertisement_id',2)
            ->limit(1)
            ->orderByDesc('id')
            ->get();
        $adds3 = AdvertisementProduct::with(['product.pictures', 'picture'])->where('advertisement_id',3)
            ->limit(1)
            ->orderByDesc('id')
            ->get();
        $adds4 = AdvertisementProduct::with(['product.pictures', 'picture'])->where('advertisement_id',4)
            ->limit(1)
            ->orderByDesc('id')
            ->get();
        $adds5 = AdvertisementProduct::with(['product.pictures', 'picture'])->where('advertisement_id',5)
            ->limit(1)
            ->orderByDesc('id')
            ->get();
        $addvertisements = new Collection();
        $addvertisements1 = $addvertisements->merge($adds1);
        $addvertisements2 = $addvertisements1->merge($adds2);
        $addvertisements3 = $addvertisements2->merge($adds3);
        $addvertisements4 = $addvertisements3->merge($adds4);
        $addvertisements5 = $addvertisements4->merge($adds5);
        $results = indexMethodResource::collection($addvertisements5)->response()->getData();
        $results1['data'] = $results;
        $results1['success'] = true;
        return $results1;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvertisementProduct $advertisementProduct)
    {
        if(Auth::user()->role){
            return response()->json(['message'=>'صلاحيات الحذف خاصة بمدير البرنامج','success'=>true]);
        }else{
            $advertisementProduct->delete();
            return response()->json(['message'=>'تم حذف المنتج من الاعلان بنجاح','success'=>true]);
        }
    }
}
