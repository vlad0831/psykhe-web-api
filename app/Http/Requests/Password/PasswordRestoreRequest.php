<?php

namespace App\Http\Requests\Password;

use App\Exceptions\PasswordRecoveryRequestException;
use App\Http\Requests\Request;
use Exception;
use Illuminate\Support\Facades\Crypt;

class PasswordRestoreRequest extends Request
{
    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @throws PasswordRecoveryRequestException
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->timestamp = Crypt::decryptString($this->input('timestamp'));
            $this->email     = Crypt::decryptString($this->input('email'));
            $this->password  = $this->input('password');
        } catch (Exception $controllerException) {
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while restoring your password';

            throw new PasswordRecoveryRequestException($errorMessage);
        }

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
                'string',
            ],
            'timestamp' => [
                'required',
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
