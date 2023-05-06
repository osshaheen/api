<?php

namespace App\Http\Requests\V1\Cities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NewCityRequest extends FormRequest
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
            'name'=>'required|string|unique:cities,name',
            'delivery_cost'=>'required|numeric',
            'country_id'=>'required|numeric|exists:countries,id'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'يرجى ادخال اسم المدينة',
            'name.string' => 'اسم الدولة من نوع نص',
            'name.unique' => 'اسم الدولة موجود مسبقا',
            'delivery_cost.required' => 'يرجى ادخال تكلفة التوصيل للمدينة',
            'delivery_cost.numeric' => 'الرجاء ادخال قيمة عددية لتكلفة التوصيل',
            'country_id.required' => 'يرجى ادخال اسم الدولة',
            'country_id.numeric' => 'يرجى ارسال معرف الدولة ال id',
            'country_id.exists' => 'معرف الدولة المرسل غير موجود',
        ];
    }
}
