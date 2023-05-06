<?php

namespace App\Http\Requests\V1\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CloseWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return empty(Auth::user()->type) && empty(Auth::user()->role);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id'=>'required|numeric|exists:users,id',
            'asset_file'=>'sometimes|image|mimes:jpg,png,jpeg|nullable',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'يرجى ادخال معرف المستخدم المطلوب اغلاق محفظته',
            'user_id.exists' => 'معرف المستخدم المطلوب اغلاق محفظته غير صحيح',
            'asset_file.mimes' => 'يرجى ادخال صورة من نوع png او jpg او jpeg'
        ];
    }
}
