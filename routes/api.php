<?php

use App\Http\Controllers\PersonalityTest\SurveyQuestionsController;
use App\Http\Controllers\PersonalityTest\VisualQuestionSetsController;
use App\Http\Controllers\Query\QueryController;
use App\Http\Controllers\Query\QueryFilterController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\OptionsController;
use App\Http\Controllers\User\PasswordRecoveryRequestController;
use App\Http\Controllers\User\PasswordRestoreController;
use App\Http\Controllers\User\PersonalityTest\CompletionController;
use App\Http\Controllers\User\PersonalityTest\SurveyQuestionResponsesController;
use App\Http\Controllers\User\PersonalityTest\VisualQuestionResponsesController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\User\Savelist\SavelistProductsController;
use App\Http\Controllers\User\Savelist\SavelistsController;
use App\Http\Controllers\User\VerificationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [
    AuthController::class,
    'login',
])->name('user.auth.login');

Route::post('/register', [
    RegistrationController::class,
    'store',
])->name('registration.store');

Route::post('/email/resend ', [
    VerificationController::class,
    'resend',
])->name('verification.resend');

Route::post('/email/verify', [
    VerificationController::class,
    'verify',
])->name('verification.verify');

Route::post('/recover-password', [
    PasswordRecoveryRequestController::class,
    'index',
])->name('password.request');

Route::post('/password-reset', [
    PasswordRestoreController::class,
    'update',
])->name('password.reset');

Route::get('/query/filters', [
    QueryFilterController::class,
    'index',
])->name('query.filters');

Route::get('/query/{id}/{checkpoint?}', [
    QueryController::class,
    'resolve',
])->name('query.resolve');

Route::post('/query', [
    QueryController::class,
    'create',
])->name('query.create');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [
        AuthController::class,
        'show',
    ])->name('user.auth.show');

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ])->name('user.auth.logout');

    Route::post('/user/profile/skip', [
        ProfileController::class,
        'skipping',
    ]);

    Route::post('/user/profile', [
        ProfileController::class,
        'update',
    ])->name('user.profile.update');

    Route::get('/user/options', [
        OptionsController::class,
        'index',
    ])->name('user.profile.options');

    Route::patch('/user/options', [
        OptionsController::class,
        'update',
    ]);

    Route::post('/user/referral', [
        ReferralController::class,
        'save',
    ])->name('user.referral.create');

    // Personality test questions
    Route::get('/personality-test/survey-questions', [
        SurveyQuestionsController::class,
        'index',
    ])->name('personality-test.survey-questions');

    Route::get('/personality-test/visual-question-sets', [
        VisualQuestionSetsController::class,
        'index',
    ]);

    // Personality test responses
    Route::get(
        '/user/personality-test/survey-question-responses',
        [
            SurveyQuestionResponsesController::class,
            'index',
        ]
    )->name('user.personality-test.survey-question-responses');

    Route::put(
        '/user/personality-test/survey-question-responses',
        [
            SurveyQuestionResponsesController::class,
            'store',
        ]
    )->name('user.personality-test.survey-question-responses.store');

    Route::get('/user/personality-test/visual-question-responses', [
        VisualQuestionResponsesController::class,
        'index',
    ])->name('user.personality-test.visual-question-responses');

    Route::put('/user/personality-test/visual-question-responses', [
        VisualQuestionResponsesController::class,
        'store',
    ])->name('user.personality-test.visual-question-responses.store');

    // Complete personality test
    Route::post('/user/personality-test/complete', [
        CompletionController::class,
        'index',
    ])->name('user.personality-test.complete');

    // Savelists
    Route::get('/user/savelists', [
        SavelistsController::class,
        'index',
    ])->name('user.savelists');

    Route::put('/user/savelists', [
        SavelistsController::class,
        'store',
    ])->name('user.savelists.store');

    Route::patch('/user/savelists/{slug}', [
        SavelistsController::class,
        'update',
    ])->name('user.savelists.update');

    Route::delete('/user/savelists/{slug}', [
        SavelistsController::class,
        'destroy',
    ])->name('user.savelists.destroy');

    // Savelist products
    Route::put('/user/savelists/{savelistSlug}/add/{productIdentifier}', [
        SavelistProductsController::class,
        'add',
    ])->name('user.savelists.products.add');

    Route::put('/user/savelists/{savelistSlug}/remove/{productIdentifier}', [
        SavelistProductsController::class,
        'remove',
    ])->name('user.savelists.products.remove');
});
