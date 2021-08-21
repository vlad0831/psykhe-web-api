<?php

namespace App\Http\Controllers\User\PersonalityTest;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PersonalityTest\SurveyQuestionResponseStoreRequest;
use App\Http\Resources\SurveyResponseResource;
use App\Repositories\User\PersonalityTest\SurveyResponseRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SurveyQuestionResponsesController extends ApiController
{
    /**
     * @var SurveyResponseRepository
     */
    private $surveyResponseRepository;

    /**
     * @param SurveyResponseRepository $surveyResponseRepository
     *
     * @return void
     */
    public function __construct(SurveyResponseRepository $surveyResponseRepository)
    {
        $this->surveyResponseRepository = $surveyResponseRepository;
    }

    /**
     * Retrieve all responses for the user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            /** @var Collection $responses */
            $responses = $this->surveyResponseRepository->findAllForUser(
                $request->user()
            );

            return $this->responseOk(
                SurveyResponseResource::collection($responses)
            );
        } catch (Exception $controllerException) {
            report($controllerException);

            return $this->responseInternalError($controllerException->getMessage());
        }
    }

    /**
     * Sets the survey responses for the user to the provided array.
     *
     * @param SurveyQuestionResponseStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(SurveyQuestionResponseStoreRequest $request)
    {
        try {
            $user      = $request->user();
            $validated = $request->validated();

            $this->surveyResponseRepository->deleteResponsesForUser($user);
            $this->surveyResponseRepository->createResponsesForUser($user, $validated['responses']);

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);

            return $this->responseInternalError($controllerException->getMessage());
        }
    }
}
