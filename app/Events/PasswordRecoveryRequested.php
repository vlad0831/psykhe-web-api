<?php

namespace App\Events;

class PasswordRecoveryRequested
{
    /**
     * @var string
     */
    public $email;

    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
