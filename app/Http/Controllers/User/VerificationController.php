<?php

namespace App\Http\Controllers\User;

use App\Events\UserRegisteredVerificationRequested;
use App\Exceptions\ActionLinkTokenExpired;
use App\Exceptions\UserEmailAlreadyVerifiedException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\User\ResendVerificationRequest;
use App\Http\Requests\User\VerificationRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class VerificationController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param \App\Repositories\User\UserRepository $userRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Http\Requests\User\VerificationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(VerificationRequest $request)
    {
        try {
            /** @var User $user */
            $user = $this->userRepository->findOneBy([
                'id'    => $request->userId,
                'email' => $request->userEmail,
            ]);

            if (! $user->isActivationTokenValid($request->timestamp)) {
                throw new ActionLinkTokenExpired('Activation link expired');
            }

            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            } else {
                throw new UserEmailAlreadyVerifiedException('User has already been activated');
            }

            return $this->responseNoContent();
        } catch (ActionLinkTokenExpired $actionLinkTokenExpired) {
            return $this->responseInternalError($actionLinkTokenExpired->getMessage());
        } catch (UserEmailAlreadyVerifiedException $userEmailAlreadyVerifiedException) {
            return $this->responseInternalError($userEmailAlreadyVerifiedException->getMessage());
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while verifying you account email';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * @param ResendVerificationRequest $request
     *
     * @return JsonResponse
     */
    public function resend(ResendVerificationRequest $request)
    {
        /** @var User $user */
        if (! $user = $this->userRepository->findOneBy(['email' => $request->email])) {
            return $this->responseNotFound('User could not be found.');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->responseInternalError('User already verified.');
        }

        event(new UserRegisteredVerificationRequested($user));

        return $this->responseNoContent();
    }
}
