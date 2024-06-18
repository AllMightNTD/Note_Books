<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Log;

class RestaurantRequest extends BaseRequest
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
            'name' => 'required|unique:restaurants,name,' . request()->id,
            'address'=> 'required',
            'contact_phone' => 'required',
            'email' => 'required|email'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nhà hàng không được bỏ trống',
            'name.unique' => 'Nhà hàng đã tồn tại',
            'contact_phone.required' => 'Số điện thoại liên hệ không được bỏ trống',
            'email' => 'Email không được bỏ trống',
            'email.email' => 'Email phải đúng định dạng'
        ];
    }
}
