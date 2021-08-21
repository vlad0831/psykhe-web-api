<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\User\Profile;
use App\Repositories\User\ProfileRepository;
use App\Repositories\User\UserRepository;
use App\Services\Profile\UserProfileAvatarService;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProfileController extends ApiController
{
    /**
     * @var UserProfileAvatarService
     */
    private $userProfileAvatarService;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param ProfileRepository        $profileRepository
     * @param UserProfileAvatarService $userProfileAvatarService
     */
    public function __construct(
        ProfileRepository $profileRepository,
        UserRepository $userRepository,
        UserProfileAvatarService $userProfileAvatarService
    ) {
        $this->profileRepository        = $profileRepository;
        $this->userRepository           = $userRepository;
        $this->userProfileAvatarService = $userProfileAvatarService;
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            /** @var Profile $profile */
            $profile = Auth::user()->profile;

            function replaceStringNull($data)
            {
                foreach ($data as $key => $value) {
                    if (is_array($data[$key])) {
                        $data[$key] = replaceStringNull($data[$key]);
                    }

                    if ($value === 'null') {
                        $data[$key] = null;
                    }
                }

                return $data;
            }

            $attributes = replaceStringNull($request->validated());

            $this->profileRepository->update($profile, $attributes);

            $this->userRepository->markUserProfileNag($user);

            $this->userProfileAvatarService->updateProfileAvatar($profile, $request->input('image'));

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while saving your account profile information';

            return $this->responseInternalError($errorMessage);
        }
    }

    public function skipping()
    {
        /** @var User $user */
        $user = Auth::user();

        $this->userRepository->markUserProfileNag($user);
    }
}
