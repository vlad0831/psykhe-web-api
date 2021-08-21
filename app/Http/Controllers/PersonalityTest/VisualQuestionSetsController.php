<?php

namespace App\Http\Controllers\PersonalityTest;

use App\Http\Controllers\ApiController;
use App\Http\Resources\VisualQuestionSetResource;
use App\Repositories\PersonalityTest\VisualQuestionSetRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisualQuestionSetsController extends ApiController
{
    /**
     * @var VisualQuestionSetRepository
     */
    private $visualQuestionRepository;

    /**
     * @param VisualQuestionSetRepository $visualQuestionRepository
     *
     * @return void
     */
    public function __construct(VisualQuestionSetRepository $visualQuestionRepository)
    {
        $this->visualQuestionRepository = $visualQuestionRepository;
    }

    /**
     * Retrieve all visual questions.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $sets = $this->visualQuestionRepository->findAllWithRelationships($request->toArray());

            return $this->responseOk(
                VisualQuestionSetResource::collection($sets)
            );
        } catch (Exception $controllerException) {
            report($controllerException);

            return $this->responseInternalError($controllerException->getMessage());
        }
    }
}
