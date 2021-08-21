<?php

namespace App\Http\Controllers\User\PersonalityTest;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PersonalityTest\VisualQuestionResponseStoreRequest;
use App\Http\Resources\VisualResponseResource;
use App\Repositories\User\PersonalityTest\VisualResponseRepository;
use App\Services\Psykhe\PsykheDirectoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VisualQuestionResponsesController extends ApiController
{
    /**
     * @var VisualResponseRepository
     */
    private $visualResponseRepository;

    /**
     * @var PsykheDirectoryService
     */
    private $directoryService;

    /**
     * @param VisualResponseRepository $visualResponseRepository
     * @param PsykheDirectoryService   $directoryService
     *
     * @return void
     */
    public function __construct(VisualResponseRepository $visualResponseRepository, PsykheDirectoryService $directoryService)
    {
        $this->visualResponseRepository = $visualResponseRepository;
        $this->directoryService         = $directoryService;
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
            $responses = $this->visualResponseRepository->findAllForUser(
                $request->user()
            );

            return $this->responseOk(
                VisualResponseResource::collection($responses)
            );
        } catch (Exception $controllerException) {
            report($controllerException);

            return $this->responseInternalError($controllerException->getMessage());
        }
    }

    /**
     * Saves the provided array of visual question responses.
     *
     * @param VisualQuestionResponseStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(VisualQuestionResponseStoreRequest $request)
    {
        try {
            $user      = $request->user();
            $validated = $request->validated();

            $this->visualResponseRepository->deleteResponsesForUser($user);
            $this->visualResponseRepository->createResponsesForUser($user, $validated['responses']);

            // Update the users preferences if they have already completed the PT
            if ($user->pt_transmitted) {
                $this->directoryService->updateUser($user);
            }

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);

            return $this->responseInternalError($controllerException->getMessage());
        }
    }
}
