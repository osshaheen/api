<?php

namespace App\Http\Requests\V1\billing_addresses;

use Illuminate\Foundation\Http\FormRequest;

class updateBillingAddressRequest extends FormRequest
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
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'company_name'=>'sometimes|string|nullable',
            'city_id'=>'required|numeric|exists:cities,id',
            'street_address'=>'required|string',
            'zip_code'=>'required|string',
            'phone'=>'required|string',
            'email'=>'required|email'
        ];
    }
    public function messages()
    {
        return [
            'email.required'=>'يرجى ادخال البريد الالكتروني',
            'email.email'=>'البريد الالكتروني غير صالح',
            'phone.required'=>'يرجى ادخال رقم الهاتف',
            'zip_code.required'=>'يرجى ادخال الرمز البريدي',
            'street_address.required'=>'يرجى ادخال اسم الشارع',
            'first_name.required'=>'يرجى ادخال الاسم',
            'last_name.required'=>'يرجى ادخال اسم العائلة',
            'city_id.required'=>'يرجى اضافة المدينة',
            'city_id.numeric'=>'معرف المدينة عددي',
            'city_id.exists'=>'المدينة المدخلة غير موجودة',
        ];
    }
}
