<?php

namespace App\Http\Controllers\User\PersonalityTest;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\MailChimp\MailChimp;
use App\Services\Psykhe\PsykheDirectoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class CompletionController extends ApiController
{
    use SerializesModels;

    /**
     * @var UserRepository
     */
    private $userRepository;

    private PsykheDirectoryService $directoryService;

    private $mailchimp;

    /**
     * @param PsykheDirectoryService $directoryService
     * @param UserRepository         $userRepository
     * @param MailChimp              $mailchimp
     */
    public function __construct(PsykheDirectoryService $directoryService, UserRepository $userRepository, MailChimp $mailchimp)
    {
        $this->userRepository   = $userRepository;
        $this->mailchimp        = $mailchimp;
        $this->directoryService = $directoryService;
    }

    /**
     * Mark the personality test as complete for the requesting user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $userWithRelations = User::where(['identifier' => $user->identifier])
                ->with('surveyResponses', 'visualResponses', 'surveyResponses.question', 'surveyResponses.answer')
                ->first()
            ;

            if ($userWithRelations->pt_complete && ! $userWithRelations->pt_transmitted) {
                $this->directoryService->CreateUser($user);

                $db_traits = $userWithRelations->traits()->firstOrNew([]);

                if (! $db_traits->exists || ! $db_traits->ocean) {
                    $db_traits->ocean = null;
                    $db_traits->save();
                }

                $this->mailchimp->addTag($userWithRelations->email, 'ptcomplete');

                $this->userRepository->markUserPtCompleted($userWithRelations);
            }

            return $this->responseNoContent();
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorCode = $controllerException->getCode();

            switch ($errorCode) {
                case 409:
                    $errorMessage = config('app.debug') === true ? $controllerException->getMessage() : 'There has been an error calculating your results';

                    return $this->responseInternalError($errorMessage);
                    break;
                default:
                    $errorMessage = config('app.debug') === true ? $controllerException->getMessage() : 'Your results are being calculated';

                    return $this->responseBadRequest($errorMessage);
            }
        }
    }
}
