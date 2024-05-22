<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class InformationRequest extends BaseRequest
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
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email phải được nhập',
            'name.required' => 'Tên phải được nhập',
            'email.regex' => 'Email sai định dạng '
        ];
    }
}
