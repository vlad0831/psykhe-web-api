<?php

namespace App\Repositories\User\PersonalityTest;

use App\Models\User;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Model;

class BaseResponseRepository extends CoreRepository
{
    /**
     * @param Model $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Retrieves all responses for a given user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function findAllForUser(User $user)
    {
        return $this->getQuery()
            ->where('user_id', $user->id)
            ->get()
            ;
    }

    /**
     * Creates responses for a given user.
     *
     * @param User  $user
     * @param array $responses
     *
     * @return mixed
     */
    public function createResponsesForUser(User $user, array $responses)
    {
        return $this->getQuery()->insert(
            array_map(function ($response) use ($user) {
                return array_merge(
                    ['user_id' => $user->id], $response
                );
            }, $responses)
        );
    }

    /**
     * Deletes all responses made by a user.
     *
     * @param User $user
     */
    public function deleteResponsesForUser(User $user)
    {
        $this->getQuery()
            ->where(['user_id' => $user->id])
            ->delete()
        ;
    }
}
