<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait UserAuthTrait
{
    /**
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    protected function authenticateUser(string $email, string $password)
    {
        return Auth::attempt([
            'email'    => $email,
            'password' => $password,
        ]);
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    protected function generateAuthToken(User $user)
    {
        return $user->createToken($user->email)->plainTextToken;
    }
}
