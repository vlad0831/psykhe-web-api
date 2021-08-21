<?php

namespace App\Http\Controllers\User;

use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserNotAuthenticatedException;
use App\Exceptions\UserNotAvailableException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    use UserAuthTrait;

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

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            /** @var User $user */
            $user = $this->userRepository->findOneBy(
                [
                    'email' => $request->get('email'),
                ]
            );

            if (! $user) {
                throw new UserNotAvailableException('Invalid account credentials provided');
            }

            if (config('psykhe.auth.email_verification_required') && is_null($user->email_verified_at)) {
                throw new UserNotActiveException('User email has not been verified. Please review your email inbox for email verification link.');
            }

            if (! $this->authenticateUser($request->get('email'), $request->get('password'))) {
                throw new AuthenticationException('Invalid account credentials provided');
            }

            return $this->responseOk(
                [
                    'token' => $this->generateAuthToken($user),
                ]
            );
        } catch (UserNotAvailableException $userNotAvailableException) {
            return $this->responseInternalError($userNotAvailableException->getMessage());
        } catch (UserNotActiveException $userNotActiveException) {
            return $this->responseInternalError($userNotActiveException->getMessage(), 'verification_pending');
        } catch (AuthenticationException $authenticationException) {
            return $this->responseInternalError($authenticationException->getMessage());
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error during the authentication process';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (! $user) {
                throw new UserNotAuthenticatedException('No user is currently authenticated');
            }

            $user->tokens()->delete();

            return $this->responseNoContent();
        } catch (UserNotAuthenticatedException $userNotAuthenticatedException) {
            return $this->responseInternalError($userNotAuthenticatedException->getMessage());
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error during the logout process';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * @return JsonResponse
     */
    public function show()
    {
        try {
            if (Auth::user()) {
                return $this->responseOk(UserResource::make(Auth::user()));
            }

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while requesting your user information';

            return $this->responseInternalError($errorMessage);
        }
    }
}
