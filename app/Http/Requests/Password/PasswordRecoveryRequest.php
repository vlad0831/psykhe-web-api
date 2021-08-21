<?php

namespace App\Http\Requests\Password;

use App\Http\Requests\Request;

class PasswordRecoveryRequest extends Request
{
    /**
     * @var string
     */
    public $email;

    /**
     * @return bool
     */
    public function authorize()
    {
        $this->email = $this->input('email');

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
            ],
        ];
    }
}
