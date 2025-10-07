<?php

use App\Http\Controllers\{
    AuthController,
    DevelopmentApplicantController,
    DevelopmentController,
    EventController,
    EventParticipantController,
    FamilyMemberController,
    HeadOfFamilyController,
    ProfileController,
    SocialAssistanceController,
    SocialAssistanceRecipientController,
    UserController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['force.json'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        Route::apiResource('user', UserController::class);
        Route::get('user/all/paginated', [UserController::class, 'getAllPaginated']);

        Route::apiResource('head-of-family', HeadOfFamilyController::class);
        Route::get('head-of-family/all/paginated', [HeadOfFamilyController::class, 'getAllPaginated']);

        Route::apiResource('family-member', FamilyMemberController::class);
        Route::get('family-member/all/paginated', [FamilyMemberController::class, 'getAllPaginated']);

        Route::apiResource('social-assistance', SocialAssistanceController::class);
        Route::get('social-assistance/all/paginated', [SocialAssistanceController::class, 'getAllPaginated']);

        Route::apiResource('social-assistance-recipient', SocialAssistanceRecipientController::class);
        Route::get('social-assistance-recipient/all/paginated', [SocialAssistanceRecipientController::class, 'getAllPaginated']);

        Route::apiResource('event', EventController::class);
        Route::get('event/all/paginated', [EventController::class, 'getAllPaginated']);

        Route::apiResource('event-participant', EventParticipantController::class);
        Route::get('event-participant/all/paginated', [EventParticipantController::class, 'getAllPaginated']);

        Route::apiResource('development', DevelopmentController::class);
        Route::get('development/all/paginated', [DevelopmentController::class, 'getAllPaginated']);

        Route::apiResource('development-applicant', DevelopmentApplicantController::class);
        Route::get('development-applicant/all/paginated', [DevelopmentApplicantController::class, 'getAllPaginated']);

        Route::get('profile', [ProfileController::class, 'index']);
        Route::post('profile', [ProfileController::class, 'store']);
        Route::put('profile', [ProfileController::class, 'update']);
    });
});
