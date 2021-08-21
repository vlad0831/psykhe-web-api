<?php

namespace App\Listeners;

use App\Events\Referral;
use App\Services\MailChimp\MailChimp;

class EnsureMarketingReferralRegistered
{
    /**
     * @var MailChimp
     */
    protected $mailchimp;

    public function __construct(MailChimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    public function handle(Referral $event)
    {
        $tags = $this->mailchimp->getTags($event->referral->to_email);

        $merge_fields = array_merge(
            $event->referral->to_name ? ['REF_NAME' => $event->referral->to_name] : [],
            ($event->referral->nonce) ?
                ['REF_NONCE' => $event->referral->nonce] : [],
            ($event->referral->user && $event->referral->user->profile && $event->referral->user->profile->name_first) ?
                ['REF_RFNAME' => $event->referral->user->profile->name_first] : [],
            ($event->referral->user && $event->referral->user->profile && $event->referral->user->profile->name_last) ?
                ['REF_RLNAME' => $event->referral->user->profile->name_last] : []
        );

        if (is_array($tags)) {
            if (! $event->force) {
                // do not send referral emails to those already known through
                // some other means
                return;
            }

            if (! in_array('referred', $tags)) {
                // do not send referral email data to those not originally
                // referred, even if we are told to "force" a send.
                return;
            }

            $result = $this->mailchimp->update(
                $event->referral->to_email,
                $merge_fields
            );

            return;
        }

        $result = $this->mailchimp->registerUnsubscribed(
            $event->referral->to_email,
            $merge_fields
        );

        if ($result) {
            $this->mailchimp->addTag($event->referral->to_email, 'referred');
        }
    }
}
