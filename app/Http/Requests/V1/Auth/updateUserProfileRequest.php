<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateUserProfileRequest extends FormRequest
{
    public $user;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $this->user = Auth::user();
//        dd($this->toArray());
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'country_id' => 'required|numeric|exists:countries,id',
            'password' => 'sometimes|nullable',
            'c_password' => 'sometimes|same:password|nullable',
            'address' => 'sometimes|string|nullable',
            'role' => 'sometimes|in:1,2,3|nullable',
            'asset_file'=>'sometimes|mimes:jpg,png,jpeg|nullable'
        ];
    }
    public function messages()
    {
        $role_message = 'اختر نوع نشاط المستخدم 1-فردي او 2-نشاط تجاري أو 3-شركة';
        if($this->user->type == 3){
            $role_message = 'اختر نوع نشاط المستخدم 1-فردي او 2-نشاط تجاري';
        }
        return [
            'name.required' => 'يرجى ادخال الاسم',
            'name.string' => 'الاسم من نوع نص',
            'country_id.required' => 'يرجى ادخال الدولة',
            'country_id.numeric' => 'معرف الدولة id يجب ان يكون من نوع عدد',
            'country_id.exists' => 'الدولة المطلوبة غير موجودة في البرنامج',
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.email' => 'البريد يجب ان يكون من نوع ايميل',
            'email.unique' => 'يجب ان يكون البريد الالكتروني فريد ، البريد '.$this->email.' موجود مسبقا',
            'c_password.same' => 'كلمة المرور غير متطابقة',
            'asset_file.mimes' => 'يرجى ارفاق صورة من نوع png,jpg,jpeg',
            'role.required' => 'نوع المستخدم مطلوب',
            'role.in' => $role_message
        ];
    }
}
