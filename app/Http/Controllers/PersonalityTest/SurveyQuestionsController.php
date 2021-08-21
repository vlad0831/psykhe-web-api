<?php

namespace App\Http\Controllers\PersonalityTest;

use App\Http\Controllers\ApiController;
use App\Http\Resources\SurveyQuestionResource;
use App\Repositories\PersonalityTest\SurveyQuestionRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class SurveyQuestionsController extends ApiController
{
    /**
     * @var SurveyQuestionRepository
     */
    private $surveyQuestionRepository;

    /**
     * @param SurveyQuestionRepository $surveyQuestionRepository
     *
     * @return void
     */
    public function __construct(SurveyQuestionRepository $surveyQuestionRepository)
    {
        $this->surveyQuestionRepository = $surveyQuestionRepository;
    }

    /**
     * Retrieve all survey questions.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $questions = $this->surveyQuestionRepository->findAllWithAnswers();

            return $this->responseOk(
                SurveyQuestionResource::collection($questions)
            );
        } catch (Exception $controllerException) {
            report($controllerException);

            return $this->responseInternalError($controllerException->getMessage());
        }
    }
}
