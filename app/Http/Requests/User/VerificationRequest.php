<?php

namespace App\Http\Requests\User;

use App\Exceptions\UserAccountVerificationException;
use App\Http\Requests\Request;
use Exception;
use Illuminate\Support\Facades\Crypt;

class VerificationRequest extends Request
{
    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $userEmail;

    /**
     * @throws UserAccountVerificationException
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->timestamp = Crypt::decryptString($this->input('timestamp'));
            $this->userId    = Crypt::decryptString($this->input('id'));
            $this->userEmail = Crypt::decryptString($this->input('email'));
        } catch (Exception $exception) {
            $errorMessage = config('app.debug') ? $exception->getMessage() : 'There has been an error while restoring your password';

            throw new UserAccountVerificationException($errorMessage);
        }

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'string',
            ],
            'timestamp' => [
                'required',
                'string',
            ],
        ];
    }
}
