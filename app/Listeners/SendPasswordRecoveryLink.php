<?php

namespace App\Listeners;

use App\Events\PasswordRecoveryRequested;
use App\Mail\SendPasswordRecoveryEmail;
use Illuminate\Support\Facades\Mail;

class SendPasswordRecoveryLink
{
    /**
     * @param PasswordRecoveryRequested $event
     *
     * @return void
     */
    public function handle(PasswordRecoveryRequested $event)
    {
        Mail::send(new SendPasswordRecoveryEmail($event->email));
    }
}
