<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\CloseWalletRequest;
use App\Http\Requests\V1\Users\newUserRequest;
use App\Http\Requests\V1\Users\updateUserRequest;
use App\Http\Resources\Api\V1\Users\indexMethodResource;
use App\Http\Resources\Api\V1\Users\showMethodResource;
use App\Http\Resources\Api\V1\Users\storeMethodResource;
use App\Http\Resources\Api\V1\Users\updateMethodResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        $users = User::with(['country','adminPicture'])
            ->where('type',$type)->paginate(10);
        $results = indexMethodResource::collection($users)->response()->getData();
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
    public function store(newUserRequest $request)
    {
        $data = $request->only('name','email','password','address','mobile','type');
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $user->load(['country','adminPicture']);
        return new storeMethodResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['country','adminPicture']);
        return new showMethodResource($user);
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
    public function update(updateUserRequest $request,User $user)
    {
        $data = $request->only('name','email','password','address','mobile');
        $data['password'] = Hash::make($request->password);
        $user->update($data);
        $user->load(['country','adminPicture']);
        return new updateMethodResource($user);
    }
    public function closeVendorWallet(CloseWalletRequest $request){
        $wallet = Wallet::with(['user'])->where('user_id',$request->user_id)->where('is_paid',0)->first();
        if($wallet) {
            if(!empty($wallet->user) && $wallet->user->type == 1) {
                $wallet->update(['is_paid' => 1]);
                if ($request->file('asset_file')) {
                    $old_name = $request->file('asset_file')->getClientOriginalName();
                    $extension = $request->file('asset_file')->getClientOriginalExtension();
                    $file_name = $request->file('asset_file')->store('public', 'public');
                    $wallet->media()->delete();
                    $wallet->media()->create([
                        'name' => $file_name,
                        'old_name' => $old_name,
                        'type' => $extension,
                        'media_trigger' => 6,
                        'is_cover' => 0
                    ]);
                }
                return response()->json(['message' => 'تم اغلاق حساب محفظة التاجر ' . $wallet->user->name . ' بنجاح', 'success' => true]);
            }else{
                return response()->json(['message'=>'المستخدم '.$wallet->user->name.' ليس تاجر','success'=>false]);
            }
        }else{
            return response()->json(['message'=>'لا يوجد محفظة مفتوحة للتاجر','success'=>false]);
        }

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
