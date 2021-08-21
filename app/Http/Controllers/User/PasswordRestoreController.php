<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ActionLinkTokenExpired;
use App\Exceptions\UserNotAvailableException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Password\PasswordRestoreRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Exception;

class PasswordRestoreController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(PasswordRestoreRequest $request)
    {
        try {
            /** @var User $user */
            if (! $user = $this->userRepository->findOneBy(['email' => $request->email])) {
                throw new UserNotAvailableException('Invalid account information provided');
            }

            if (! $user->isPasswordResetTokenValid($request->timestamp)) {
                throw new ActionLinkTokenExpired('Password reset link expired');
            }

            $this->userRepository->update($user, ['password' => $request->password]);

            return $this->responseNoContent();
        } catch (UserNotAvailableException $userNotAvailableException) {
            return $this->responseInternalError($userNotAvailableException->getMessage());
        } catch (ActionLinkTokenExpired $actionLinkTokenExpired) {
            return $this->responseInternalError($actionLinkTokenExpired->getMessage());
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while restoring your password';

            return $this->responseInternalError($errorMessage);
        }
    }
}
