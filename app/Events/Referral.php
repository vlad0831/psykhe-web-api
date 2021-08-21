<?php

namespace App\Events;

use App\Models\User\Referral as UserReferral;
use Illuminate\Queue\SerializesModels;

class Referral
{
    use SerializesModels;

    /**
     * @var UserReferral
     */
    public $referral;

    /**
     * @var bool
     */
    public $force;

    public function __construct(UserReferral $referral, bool $force = false)
    {
        $this->referral = $referral;
        $this->force    = $force;
    }
}
