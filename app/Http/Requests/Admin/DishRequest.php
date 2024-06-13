<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Log;

class DishRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:dishs,name,' . request()->id,
            'price_min' => 'required',
            'price_max' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên món ăn không được bỏ trống',
            'name.unique' => 'Món ăn đã tồn tại',
            'price_min.required' => 'Giá tối thiểu không được bỏ trống',
            'price_max.required' => 'Giá tối đa không được bỏ trống'
        ];
    }
}
