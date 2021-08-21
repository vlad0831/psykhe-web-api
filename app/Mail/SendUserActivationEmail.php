<?php

namespace App\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Crypt;

class SendUserActivationEmail extends Mailable
{
    /**
     * User recipient.
     *
     * @var User
     */
    protected $user;

    /**
     * SendUserActivationNotification constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $app            = env('APP_NAME');
        $subject        = trans(':app Account Activation', ['app' => $app]);
        $siteUrl        = config('psykhe.spa.url').config('psykhe.spa.email_verification_path');
        $activationLink = $siteUrl.'/'.Crypt::encryptString(Carbon::now()->timestamp).'/'.Crypt::encryptString($this->user->id).'/'.Crypt::encryptString($this->user->email);

        return $this->to($this->user->email)
            ->view('email.auth.userActivation')
            ->subject($subject)
            ->with(
                [
                    'name'           => $this->user->profile->name_first,
                    'app'            => $app,
                    'subject'        => $subject,
                    'activationLink' => $activationLink,
                    'mailFrom'       => env('MAIL_FROM_ADDRESS'),
                ]
            )
        ;
    }
}
