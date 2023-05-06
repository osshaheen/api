<?php

namespace App\Http\Requests\V1\MobileApp;

use Illuminate\Foundation\Http\FormRequest;

class submitOrderProductRatingRequest extends FormRequest
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
            'rating'=>'required|numeric|in:0,1,2,3,4,5',
            'comment'=>'required|string',
            'order_product_id'=>'required|numeric|exists:order_products,id',
        ];
    }
    public function messages()
    {
        return [
            'rating.required'=>'التقييم مطلوب من 0 الى 5',
            'rating.numeric'=>'التقييم قيمة عددية من 0 الى 5',
            'rating.in'=>'التقييم قيمة عددية من 0 الى 5',
            'comment.required'=>'يرجى اضافة تعليق على التقييم',
            'order_product_id.required'=>'ادخل المنتج المطلوب تقييمه',
            'order_product_id.exists'=>'المنتج المطلوب تقييمه لا يوجد طلب عليه',
        ];
    }
}
