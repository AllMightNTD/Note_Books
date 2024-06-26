<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class OpeningHourRequest extends BaseRequest
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
            'day_in_week_id' => 'required',
            'open_time' => 'required',
            'close_time' => ['required' , 'after:open_time'],
            'restaurant_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'day_in_week_id.required' => 'Ngày trong tuần không được bỏ trống',
            'open_time.required' => 'Giờ mở cửa không được bỏ trống',
            'close_time.after' => 'Giờ đóng cửa phải lớn hơn giờ mở cửa',
            'close_time.required' => 'Giờ đóng cửa không được bỏ trống',
            'restaurant_id.required' => 'Nhà hàng không được bỏ trống',
        ];
    }
}
