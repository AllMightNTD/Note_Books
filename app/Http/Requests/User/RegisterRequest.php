<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required | alpha | unique:users,name',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được bỏ trống',
            'name.alpha' => "Tên chỉ được chứa các giá trị chữ cái",
            'email.required' => 'Email phải được nhập',
            'password.required' => 'Password không được để trống',
            'email.regex' => 'Email sai định dạng ',
            'email.unique' => 'Email đã tồn tại'
        ];
    }
}
