<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class UpdateOptionRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
            ],

            'value' => [
                'required',
            ],
        ];
    }
}
