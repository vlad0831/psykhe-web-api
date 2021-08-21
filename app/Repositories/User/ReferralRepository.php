<?php

namespace App\Repositories\User;

use App\Models\User\Referral;
use App\Repositories\CoreRepository;

class ReferralRepository extends CoreRepository
{
    /**
     * @param Referral $model
     *
     * @return void
     */
    public function __construct(Referral $model)
    {
        parent::__construct($model);
    }
}
