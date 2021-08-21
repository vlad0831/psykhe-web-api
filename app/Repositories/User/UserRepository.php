<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\CoreRepository;

class UserRepository extends CoreRepository
{
    /**
     * @param User $model
     *
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function markUserPtCompleted(User $user)
    {
        $this->getQuery()
            ->where('id', $user->id)
            ->update(['pt_transmitted' => true])
        ;
    }

    public function markUserProfileNag(User $user)
    {
        $this->getQuery()
            ->where('id', $user->id)
            ->update(['profile_nagged' => true])
        ;
    }
}
