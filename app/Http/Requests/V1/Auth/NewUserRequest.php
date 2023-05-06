<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
{
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
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'country_id' => 'required|numeric|exists:countries,id',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'address' => 'sometimes|string|nullable',
            'mobile' => 'sometimes|string|nullable',
            'type' => 'required|in:1,2'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'يرجى ادخال الاسم',
            'name.string' => 'الاسم من نوع نص',
            'country_id.required' => 'يرجى ادخال الدولة',
            'country_id.numeric' => 'معرف الدولة id يجب ان يكون من نوع عدد',
            'country_id.exists' => 'الدولة المطلوبة غير موجودة في البرنامج',
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.email' => 'البريد يجب ان يكون من نوع ايميل',
            'email.unique' => 'يجب ان يكون البريد الالكتروني فريد ، البريد '.$this->email.' موجود مسبقا',
            'password.required' => 'يرجى ادخال كلمة المرور',
            'c_password.required' => 'يرجى تأكيد كلمة المرور',
            'type.required' => 'نوع المستخدم مطلوب',
            'type.in' => 'اختر نوع المستخدم بائع او مشتري'
        ];
    }
}
