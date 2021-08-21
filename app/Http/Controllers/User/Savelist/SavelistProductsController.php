<?php

namespace App\Http\Controllers\User\Savelist;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Services\Psykhe\Contracts\PsykheDirectoryService;
use Exception;
use Illuminate\Support\Facades\Auth;

class SavelistProductsController extends ApiController
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

    public function add(string $savelistSlug, int $productIdentifier)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $response = $this->directoryService->addToSavelist(
                $user, $savelistSlug, $productIdentifier
            );

            if (config('app.env') == 'test') {
                return response()->json(['message' => $response['message']], $response['status'], [], JSON_PRETTY_PRINT);
            }

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while adding the product to your savelist.';

            if ($controllerException->getCode() === 404) {
                return $this->responseNotFound($errorMessage);
            }

            return $this->responseInternalError($errorMessage);
        }
    }

    public function remove(string $savelistSlug, int $productIdentifier)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $response = $this->directoryService->removeFromSavelist(
                $user, $savelistSlug, $productIdentifier
            );

            if (config('app.env') == 'test') {
                return response()->json(['message' => $response['message']], $response['status'], [], JSON_PRETTY_PRINT);
            }

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while removing the product from your savelist.';

            if ($controllerException->getCode() === 404) {
                return $this->responseNotFound($errorMessage);
            }

            return $this->responseInternalError($errorMessage);
        }
    }
}
