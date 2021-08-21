<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class ResendVerificationRequest extends FormRequest
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
        $this->email = $this->input('email_token')
            ? Crypt::decryptString($this->input('email_token'))
            : $this->input('email_address');

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
            'email_token' => [
                'required_without:email_address',
                'string',
            ],
            'email_address' => [
                'required_without:email_token',
                'email',
            ],
        ];
    }
}
