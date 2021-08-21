<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UpdateOptionRequest;
use App\Repositories\User\ProfileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OptionsController extends ApiController
{
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

    /**
     * Retrieves the options for the requesting user.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $profile = Auth::user()->profile;

            return $this->responseOk(
                $this->profileRepository->getOptions($profile)
            );
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'An error occured while retrieving your options';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * Updates the provided key in the users options.
     *
     * @param UpdateOptionRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateOptionRequest $request)
    {
        try {
            $profile = Auth::user()->profile;

            $validated = $request->validated();

            $this->profileRepository->updateOption(
                $profile, $validated['key'], $validated['value']
            );

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'An error occured while updating your options';

            return $this->responseInternalError($errorMessage);
        }
    }
}
