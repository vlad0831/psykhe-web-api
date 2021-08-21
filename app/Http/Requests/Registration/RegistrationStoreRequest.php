<?php

namespace App\Http\Requests\Registration;

use App\Http\Requests\Request;

class RegistrationStoreRequest extends Request
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
            'name_first' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'email',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'same:password',
            ],
        ];
    }
}
