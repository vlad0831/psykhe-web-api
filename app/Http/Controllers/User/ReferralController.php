<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Referral\CreateReferralRequest;
use App\Http\Resources\ReferralResource;
use App\Services\Referral\ReferralService;
use Exception;
use Illuminate\Http\JsonResponse;

class ReferralController extends ApiController
{
    /**
     * @var ReferralService
     */
    private $referralService;

    /**
     * @param ReferralService $referralService
     *
     * @return void
     */
    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * @param CreateReferralRequest $request
     *
     * @return JsonResponse
     */
    public function save(CreateReferralRequest $request)
    {
        try {
            $referral = $this->referralService->createReferral($request->user(), $request->validated());

            return $this->responseOk(ReferralResource::make($referral));
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while processing your referral';

            return $this->responseInternalError($errorMessage);
        }
    }
}
