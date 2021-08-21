<?php

namespace App\Events;

use App\Models\User\Referral;

class ReferralWasAdded
{
    /**
     * @var Referral
     */
    public $referral;

    /**
     * @param Referral $referral
     *
     * @return void
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }
}
