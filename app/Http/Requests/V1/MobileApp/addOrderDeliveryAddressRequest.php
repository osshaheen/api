<?php

namespace App\Http\Requests\V1\MobileApp;

use Illuminate\Foundation\Http\FormRequest;

class addOrderDeliveryAddressRequest extends FormRequest
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
            'apartment'=>'required|string',
            'unit'=>'required|string',
            'first_name'=>'required|string',
            'last_name'=>'sometimes|string|nullable',
            'email'=>'sometimes|string|nullable',
            'company_name'=>'sometimes|string|nullable',
            'street_address'=>'required|string',
            'phone'=>'required|string',
        ];
    }
    public function messages()
    {
        return [
            'unit.required'=>'يرجى ادخال رقم العمارة',
            'apartment.required'=>'يرجى ادخال رقم الشقة',
            'first_name.required'=>'يرجى ادخال الاسم',
            'street_address.required'=>'يرجى ادخال عنوان الشارع',
            'phone.required'=>'يرجى ادخال رقم موبايل التواصل',
        ];
    }
}
