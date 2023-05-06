<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class approveProductRequest extends FormRequest
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
            'product_id'=>'required|numeric|exists:products,id'
        ];
    }
    public function messages()
    {
        return [
            'product_id.required' => 'يرجى اضافة معرف المنتج',
            'product_id.numeric' => 'معرف المنتج من نوع عدد',
            'product_id.exists' => 'المنتج غير موجود'
        ];
    }
}
