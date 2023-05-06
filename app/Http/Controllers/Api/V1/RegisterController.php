<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\NewUserRequest;
use App\Http\Requests\V1\Auth\updateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(NewUserRequest $request)
    {
        $input = $request->only('name','email','password','type','mobile','country_id','address');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->token = $user->createToken('MyApp')->plainTextToken;
        $user->success = true;
        return response()->json($user);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
//        dd(Auth::attempt(['email' => $request->email, 'password' => $request->password]),['email' => $request->email, 'password' => $request->password]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            if($user->blocked){
                return response()->json(['message'=>'المستخدم '.$user->name.' تم حظره']);
            }
            $user->token = $user->createToken('MyApp')->plainTextToken;
            $user->success = true;
            return response()->json($user);
        }
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }
    public function getUserProfile(){
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
    public function blockUser(User $user){
        if($user->id ==1){
            return response()->json(['message' => 'لا تمتك صلاحيات حظر مدير النظام الاساسي']);
        }
        if(Auth::user()->type == 0) {
            $user->update(['blocked' => 1]);
            return response()->json(['message' => 'تم حظر المستخدم ' . $user->name . ' من البرنامج']);
        }else{
            return response()->json(['message' => 'لا تمتك صلاحيات حظر المستخدمين']);
        }
    }
    public function unBlockUser(User $user){
        if($user->id ==1){
            return response()->json(['message' => 'لا تمتك صلاحيات حظر مدير النظام الاساسي']);
        }
        if(Auth::user()->type == 0) {
            $user->update(['blocked' => 0]);
            return response()->json(['message' => 'تم فك الحظر عن المستخدم ' . $user->name]);
        }else{
            return response()->json(['message' => 'لا تمتك صلاحيات فك الحظر عن المستخدمين']);
        }
    }
}
