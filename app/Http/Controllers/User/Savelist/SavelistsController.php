<?php

namespace App\Http\Controllers\User\Savelist;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Savelist\CreateSavelistRequest;
use App\Models\User;
use App\Services\Psykhe\Contracts\PsykheDirectoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SavelistsController extends ApiController
{
    /**
     * @var PsykheDirectoryService
     */
    private PsykheDirectoryService $directoryService;

    /**
     * @param PsykheDirectoryService $directoryService
     */
    public function __construct(PsykheDirectoryService $directoryService)
    {
        $this->directoryService = $directoryService;
    }

    /**
     * Retrieve all savelists for the requesting user.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $savelists = $this->directoryService->listSavelists($user);

            return $this->responseOk($savelists);
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while retrieving your savelists';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * Create a new savelist for the requesting user.
     *
     * @param CreateSavelistRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateSavelistRequest $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $savelist = $this->directoryService->createSavelist(
                $user,
                $request->validated()['name']
            );

            return $this->responseOk($savelist);
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while creating your savelists';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * Updates the name of a savelist for the requesting user.
     *
     * @param CreateSavelistRequest $request
     * @param string                $slug
     *
     * @return JsonResponse
     */
    public function update(CreateSavelistRequest $request, string $slug)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $savelist = $this->directoryService->renameSavelist(
                $user,
                $slug,
                $request->validated()['name']
            );

            return $this->responseOk($savelist);
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while updating your savelists';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * Delete the specified savelist for the requesting user.
     *
     * @param string $slug
     *
     * @return JsonResponse
     */
    public function destroy(string $slug)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $this->directoryService->deleteSavelist(
                $user, $slug
            );

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while deleting your savelists';

            return $this->responseInternalError($errorMessage);
        }
    }
}
