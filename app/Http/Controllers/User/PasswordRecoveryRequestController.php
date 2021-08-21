<?php

namespace App\Http\Controllers\User;

use App\Events\PasswordRecoveryRequested;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Password\PasswordRecoveryRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class PasswordRecoveryRequestController extends ApiController
{
    /**
     * @param PasswordRecoveryRequest $request
     *
     * @return JsonResponse
     */
    public function index(PasswordRecoveryRequest $request)
    {
        try {
            event(new PasswordRecoveryRequested($request->email));

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error in the password recovery request process';

            return $this->responseInternalError($errorMessage);
        }
    }
}
