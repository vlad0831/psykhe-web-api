<?php

namespace App\Http\Requests\Referral;

use App\Http\Requests\Request;
use App\Rules\UnregisteredReferral;

class CreateReferralRequest extends Request
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
            'to_email' => ['required', 'email', new UnregisteredReferral($this->user())],
            'to_name'  => ['required', 'string'],
        ];
    }
}
