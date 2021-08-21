<?php

namespace App\Services\Referral;

use App\Events\ReferralWasAdded;
use App\Models\User;
use App\Models\User\Referral;
use App\Repositories\User\ReferralRepository;

class ReferralService
{
    /**
     * @var ReferralRepository
     */
    private $referralRepository;

    /**
     * @param ReferralRepository $referralRepository
     *
     * @return void
     */
    public function __construct(ReferralRepository $referralRepository)
    {
        $this->referralRepository = $referralRepository;
    }

    /**
     * @param User  $user
     * @param array $params
     *
     * @return Referral
     */
    public function createReferral(User $user, array $params)
    {
        /** @var Referral $referral */
        $referral = $this->referralRepository->getModel($params);

        $referral->user()->associate($user)->save();

        event(new ReferralWasAdded($referral));

        return $referral;
    }
}
