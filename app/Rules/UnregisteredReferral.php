<?php

namespace App\Rules;

use App\Models\User;
use App\Models\User\Referral;
use Illuminate\Contracts\Validation\Rule;

class UnregisteredReferral implements Rule
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Referral::where('to_email', $value)
            ->where('user_id', $this->user->id)
            ->count() == 0;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Referral already sent';
    }
}
