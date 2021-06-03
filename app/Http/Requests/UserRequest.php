<?php

namespace App\Http\Requests;

class UserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'limit'         => 'sometimes|min:10',
            'start_date'    => 'sometimes|date',
            'end_date'      => 'sometimes|date|after_or_equal:start_date',
            'status'        => 'sometimes|min:0|max:1',
        ];
    }

    public function messages()
    {
        return [
            'limit.min'                 => 'The minimum value for limit is 10',
            'start_date.date'           => 'Start date must be of valid date format',
            'end_date.date'             => 'End date must be of valida date format',
            'end_date.after_or_equal'   => 'End date can not be before the start date',
            'status.min'                => 'The minimum value for status is 0',
            'status.max'                => 'The maximum value for status is 1'
        ];
    }
}
