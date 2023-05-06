<?php

namespace App\Http\Requests\V1\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateProductRequest extends FormRequest
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
        $available_quantity = 'sometimes|numeric|nullable';
        $title = 'required|string';
        $is_approved = '';
        $sub_category_roles = '';
        if(Auth::user()->type == 0){
            $is_approved = 'sometimes|in:0,1,2|nullable';
        }else{
            $available_quantity = 'required|numeric';
        }
        $sub_category = Category::find($this->sub_category_id);
        if($sub_category->level == 1){
            $sub_category_roles = '|in:9789798798797987987987';
        }
        $existed_product = Product::where('title',$this->title)
            ->where('sub_category_id',$this->sub_category_id)
            ->where('seller_id',$this->seller_id)
            ->first();
        if(!empty($existed_product)&&$existed_product->id != $this->product->id){
            $title = 'unique:products,title';
        }
        return [
            'title'=>$title,
            'description'=>'required|string',
            'sub_category_id'=>'required|numeric'.$sub_category_roles.'|exists:categories,id',
            'price'=>'required|numeric',
            'discount_limit'=>'sometimes|numeric|lt:price',
            'seller_id'=>'sometimes|numeric|exists:users,id|nullable',
            'available_quantity'=>$available_quantity,
            'is_approved'=>$is_approved
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'يرجى اضافة عنوان المنتج',
            'title.string'=>'يرجى اضافة عنوان المنتج من نوع نص',
            'title.unique'=>'عنوان المنتج مكرر لنفس البائع والتصنيف',
            'description.required'=>'يرجى اضافة وصف المنتج',
            'description.string'=>'يرجى اضافة وصف نصي للمنتج',
            'price.required'=>'يرجى اضافة سعر المنتج',
            'price.numeric'=>'سعر المنتج لا يقبل الا عدد',
            'discount_limit.numeric'=>'سعر تخفيض المنتج لا يقبل الا عدد',
            'discount_limit.lt'=>'سعر تخفيض المنتج يجب ان يكون اقل من قيمة سعر المنتج',
            'sub_category_id.required'=>'يرجى اختيار تصنيف للمنتج',
            'sub_category_id.numeric'=>'يرجى اضافة معرف تصنيف المنتج من نوع عدد',
            'sub_category_id.exists'=>'يرجى اختيار تصنيف موجود',
            'sub_category_id.in'=>'لا يمكن اضافة المنتج الى تصنيف اساسي ، يرجى اختيار تصنيف فرعي',
            'seller_id.numeric'=>'يرجى اضافة معرف البائع من نوع عدد',
            'seller_id.exists'=>'يرجى اختيار بائع موجود',
            'available_quantity.required'=>'يرجى اضافة الكمية المتوفرة',
            'available_quantity.numeric'=>'الكمية المتوفرة للمنتج يجب ان تكون عدد',
            'is_approved.in'=>'للموافقة على المنتج اختر 1 لرفضه اختر 2 لتركه في حالة تحقق الادارة اختر 0'
        ];
    }
}
