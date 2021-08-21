<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Services\MailChimp\MailChimp;

class EnsureMarketingRegistered
{
    /**
     * @var MailChimp
     */
    protected $mailchimp;

    public function __construct(MailChimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    public function handle(UserRegistered $event)
    {
        $status       = $this->mailchimp->getSubscriptionStatus($event->user->email);
        $merge_fields = array_merge(
            $event->user->profile && $event->user->profile->name_first ?
                ['FNAME' => $event->user->profile->name_first] :
                [],
            $event->user->profile && $event->user->profile->name_last ?
                ['LNAME' => $event->user->profile->name_last] :
                []
        );

        if ($status === false) {
            $result = $this->mailchimp->registerSubscribed(
                $event->user->email,
                $merge_fields
            );
        } elseif ($status !== 'subscribed') {
            $this->mailchimp->updateSubscribed(
                $event->user->email,
                $merge_fields
            );
        }
    }
}
