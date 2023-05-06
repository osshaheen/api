<?php

namespace App\Http\Requests\V1\sectionProducts;

use App\Models\SectionProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class newSectionProductRequest extends FormRequest
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
        $start_date = '';
        $end_date = '';
        $existed_product_rules = '';
        if(!isset($this->status)||$this->status==0) {
            $start_date = 'required|date|date_format:Y-m-d';
            $end_date = 'required|date|date_format:Y-m-d|after:start_date';
        }
        $existed_product = SectionProduct::where('product_id',$this->product_id)->where('section_id',$this->section_id)->first();
        if($existed_product){
            $existed_product_rules = '|unique:section_products,product_id';
        }
        return [
            'product_id'=>'required|numeric|exists:products,id'.$existed_product_rules,
            'section_id'=>'required|numeric|in:1,3,4',
            'status'=>'sometimes|in:0,1|nullable',
            'start_date'=>$start_date,
            'end_date'=>$end_date,
        ];
    }
    public function messages()
    {
        return [
            'start_date.required' => 'يرجى ادخال تاريخ بداية الكوبون',
            'start_date.date' => 'تاريخ بداية الكوبون من نوع تاريخ',
            'start_date.date_format' => 'صيغة بداية تاريخ الكوبون يوم - شهر - سنة',
            'end_date.required' => 'يرجى ادخال تاريخ نهاية الكوبون',
            'end_date.date' => 'تاريخ نهاية الكوبون من نوع تاريخ',
            'end_date.date_format' => 'صيغة نهاية تاريخ الكوبون يوم - شهر - سنة',
            'end_date.after' => 'أدخل نهاية تاريخ الكوبون بعد تاريخ بداية الكوبون',
            'product_id.required' => 'يرجى ادخال المنتج',
            'product_id.in' => 'معرف المنتج من نوع عدد',
            'product_id.exists' => 'معرف المنتج محذوف',
            'product_id.unique' => 'معرف المنتج موجود مسبقا لنفس القسم',
            'section_id.required' => 'يرجى ادخال القسم',
            'section_id.in' => 'best sellers = 1,hot product = 3,best_offers = 4',
            'status.in' => 'الحالة فقط 1 او 0',
        ];
    }
}
