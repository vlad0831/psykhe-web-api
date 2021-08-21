<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'dob' => [
                'nullable',
                'string',
            ],
            'name_first' => [
                'required',
                'string',
            ],
            'name_last' => [
                'nullable',
                'string',
            ],
            'image' => [
                'nullable',
            ],
        ];
    }
}
