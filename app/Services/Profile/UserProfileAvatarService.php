<?php

namespace App\Services\Profile;

use App\Models\User;
use App\Models\User\Profile;
use App\Repositories\User\ProfileRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;

class UserProfileAvatarService
{
    const EXPECTED_MEDIA_PREFIX = 'data:image/jpeg;base64,';

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @param ProfileRepository $profileRepository
     *
     * @return void
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public static function userHasAvatar(User $user)
    {
        return $user->profile->avatar ?? false;
    }

    /**
     * @param Profile $profile
     * @param $fileData
     *
     * @throws BindingResolutionException
     */
    public function updateProfileAvatar(Profile $profile, $fileData)
    {
        if (substr($fileData, 0, strlen(self::EXPECTED_MEDIA_PREFIX)) !== self::EXPECTED_MEDIA_PREFIX) {
            return;
        }

        $avatarData = base64_decode(substr($fileData, strlen(self::EXPECTED_MEDIA_PREFIX)));

        if ($avatarData === false) {
            return;
        }

        $avatarDisk = config('psykhe.avatar.disk');
        $avatarPath = self::getAvatarPath($profile->user->identifier);

        Storage::disk($avatarDisk)->put($avatarPath, $avatarData);

        $this->profileRepository->update($profile, [
            'avatar'             => true,
            'avatar_uploaded_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    private static function getAvatarPath(string $userIdentifier)
    {
        return substr($userIdentifier, 0, 2).'/'.substr($userIdentifier, 2, 2)."/${userIdentifier}.jpg";
    }
}
