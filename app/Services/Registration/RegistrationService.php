<?php

namespace App\Services\Registration;

use App\Events\UserRegistered;
use App\Exceptions\UserRegistrationException;
use App\Models\User;
use App\Models\User\Profile;
use App\Repositories\User\ProfileRepository;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @param UserRepository    $userRepository
     * @param ProfileRepository $profileRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->userRepository    = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * @param array $params
     *
     * @throws UserRegistrationException
     *
     * @return User
     */
    public function registerUser(array $params)
    {
        try {
            DB::beginTransaction();

            /** @var Profile $profile */
            $profile = $this->profileRepository->getModel(
                // Amazon Aurora (production database) does not support default values for JSON columns.
                // We therefore need to manually initialize this column here.
                array_merge($params, [
                    'options' => new \stdClass(),
                ])
            );

            /** @var User $user */
            $user = $this->userRepository->create($params);
            $user->profile()->save($profile);

            DB::commit();

            event(new UserRegistered($user->refresh()));

            return $user;
        } catch (Exception $userRegistrationException) {
            DB::rollBack();

            throw new UserRegistrationException('User registration error: '.$userRegistrationException->getMessage());
        }
    }
}
