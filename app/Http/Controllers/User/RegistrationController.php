<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Registration\RegistrationStoreRequest;
use App\Services\Registration\RegistrationService;
use Exception;
use Illuminate\Http\JsonResponse;

class RegistrationController extends ApiController
{
    /**
     * @var RegistrationService
     */
    private $registrationService;

    /**
     * @param RegistrationService $registrationService
     *
     * @return void
     */
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * @param RegistrationStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(RegistrationStoreRequest $request)
    {
        try {
            $this->registrationService->registerUser($request->validated());

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error during the registration process';

            return $this->responseInternalError($errorMessage);
        }
    }
}
