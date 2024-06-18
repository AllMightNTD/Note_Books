<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreOrUpdateUserRequest extends BaseRequest
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
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required',
            'name' => 'required',
            'contact_phone' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên phải được nhập',
            'email.required' => 'Email phải được nhập',
            'password.required' => 'Password không được để trống',
            'email.regex' => 'Email sai định dạng ',
            'contact_phone.required' => 'Số điện thoại phải được nhập'
        ];
    }
}
