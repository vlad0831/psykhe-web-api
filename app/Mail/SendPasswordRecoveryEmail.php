<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Crypt;

class SendPasswordRecoveryEmail extends Mailable
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject   = 'Reset Your Password';
        $siteUrl   = config('psykhe.spa.url').config('psykhe.spa.email_password_reset');
        $resetLink = $siteUrl.'/'.Crypt::encryptString(Carbon::now()->timestamp).'/'.Crypt::encryptString($this->email);

        return $this->to($this->email)
            ->view('email.password.passwordReset')
            ->subject($subject)
            ->with(
                [
                    'subject'   => $subject,
                    'resetLink' => $resetLink,
                    'mailFrom'  => env('MAIL_FROM_ADDRESS'),
                ]
            )
        ;
    }
}
