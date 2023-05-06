<?php

namespace App\Http\Requests\V1\Countries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NewCountryRequest extends FormRequest
{ /**
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
            'name'=>'required|string|unique:countries,name',
            'currency'=>'required|string|unique:countries,currency',
            'currency_abbreviation'=>'required|string|unique:countries,currency_abbreviation'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'يرجى ادخال اسم الدولة',
            'name.string' => 'اسم الدولة من نوع نص',
            'name.unique' => 'اسم الدولة موجود مسبقا',
            'currency.required' => 'يرجى ادخال العملة',
            'currency.string' => 'اسم العملة من نوع نص',
            'currency.unique' => 'اسم العملة موجود مسبقا',
            'currency_abbreviation.required' => 'يرجى ادخال رمز العملة',
            'currency_abbreviation.string' => 'رمز العملة من نوع نص',
            'currency_abbreviation.unique' => 'رمز العملة موجود مسبقا',
        ];
    }
}
