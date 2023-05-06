<?php

namespace App\Http\Requests\V1\advertisementProducts;

use App\Models\AdvertisementProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateAdvertisementProductRequest extends FormRequest
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
//        dd($this->advertisementProduct->id);
        $existed_product_rules = '';
        $existed_product = AdvertisementProduct::where('product_id',$this->product_id)->where('advertisement_id',$this->location)->first();
//        dd($existed_product->toArray());
        if($existed_product){
            $existed_product_rules = '|unique:advertisement_products,id,'.$this->advertisementProduct->id;
        }
//        dd('required|numeric|exists:products,id'.$existed_product_rules);
        return [
            'product_id'=>'required|numeric|exists:products,id'.$existed_product_rules,
            'image'=>'sometimes|image|mimes:png,jpg,jpeg|nullable',
            'location'=>'required|numeric|in:1,2,3,4',
            'status'=>'sometimes|in:0,1|nullable',
        ];
    }
    public function messages()
    {
        return [
            'product_id.required' => 'يرجى ادخال المنتج',
            'product_id.in' => 'معرف المنتج من نوع عدد',
            'product_id.exists' => 'معرف المنتج محذوف',
            'product_id.unique' => 'معرف المنتج موجود مسبقا لنفس القسم وحالة الاعلان فعالة',
            'location.required' => 'مكان الاعلان مطلوب',
            'location.in' => 'يرجى اختيار مكان الاعلان من 1 او 2 او 3 او 4',
            'status.in' => 'الحالة فقط 1 او 0',
//            'image.required' => 'يرجى ادخال صورة من نوع png,jpg,jpeg',
            'image.image' => 'يرجى ادخال صورة من نوع png,jpg,jpeg',
            'image.mimes' => 'يرجى ادخال صورة من نوع png,jpg,jpeg',
        ];
    }
}
