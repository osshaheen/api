<?php

namespace App\Http\Requests\V1\ClientArea;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class addProductToCartRequest extends FormRequest
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
        $product = Product::find($this->product_id);
        $quantity = $product ? $product->available_quantity : 0;
//        dd($this->product_id);
        return [
            'product_id'=>'required|numeric|exists:products,id',
            'quantity'=>'required|numeric|gt:0'
//            'quantity'=>'required|numeric|lte:'.$quantity
        ];
    }
    public function messages()
    {
        $product = Product::find($this->product_id);
        $quantity = $product ? $product->available_quantity : 0;
        return [
            'product_id.required' => 'اضف معرف ID للمنتج',
            'product_id.numeric' => 'اضف معرف ID للمنتج من نوع عدد',
            'product_id.exists' => 'المنتج المطلوب غير موجود',
            'quantity.required' => 'اضف كمية المنتج المطلوبة',
            'quantity.numeric' => 'اضف كمية المنتج من نوع عدد',
            'quantity.gt' => 'يرجى اضافة كمية اكثر من 1',
            'quantity.lte' => 'الكمية المطلوبة اكبر من الكمية المتوفرة وهي '.$quantity,
        ];
    }
}
