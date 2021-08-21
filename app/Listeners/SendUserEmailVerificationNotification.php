<?php

namespace App\Listeners;

use App\Mail\SendUserActivationEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Mail;

class SendUserEmailVerificationNotification
{
    /**
     * @param $event
     *
     * @return void
     */
    public function handle($event)
    {
        if (! config('psykhe.auth.email_verification_required')) {
            return;
        }

        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            Mail::send(new SendUserActivationEmail($event->user));
        }
    }
}
