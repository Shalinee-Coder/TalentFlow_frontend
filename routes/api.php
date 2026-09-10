<?php

use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\InterviewController;
use App\Http\Controllers\Api\V1\JobController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ResumeController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\TechnicalTaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // --- Authentication (Public) ---
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    // Public Job and Skill browsing
    Route::get('jobs', [JobController::class, 'index']);
    Route::get('jobs/{job}', [JobController::class, 'show']);
    Route::get('skills', [SkillController::class, 'index']);
    Route::get('skills/{skill}', [SkillController::class, 'show']);

    // --- Protected Routes (Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });

        // Jobs Management
        Route::post('jobs', [JobController::class, 'store'])->middleware('role:admin,recruiter');
        Route::put('jobs/{job}', [JobController::class, 'update'])->middleware('role:admin,recruiter');
        Route::patch('jobs/{job}/status', [JobController::class, 'updateStatus'])->middleware('role:admin,recruiter');
        Route::delete('jobs/{job}', [JobController::class, 'destroy'])->middleware('role:admin,recruiter');

        // Skills Management
        Route::post('skills', [SkillController::class, 'store'])->middleware('role:admin,recruiter');
        Route::put('skills/{skill}', [SkillController::class, 'update'])->middleware('role:admin,recruiter');
        Route::delete('skills/{skill}', [SkillController::class, 'destroy'])->middleware('role:admin');

        // Resumes Management
        Route::post('resumes', [ResumeController::class, 'store'])->middleware('role:admin,recruiter,candidate');
        Route::get('resumes/{resume}', [ResumeController::class, 'show']);
        Route::get('resumes/{resume}/status', [ResumeController::class, 'status']);
        Route::get('resumes/{resume}/download', [ResumeController::class, 'download']);

        // Applications Management
        Route::post('jobs/{job}/apply', [ApplicationController::class, 'apply'])->middleware('role:candidate');
        Route::get('applications', [ApplicationController::class, 'index']);
        Route::get('applications/{application}', [ApplicationController::class, 'show']);
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->middleware('role:admin,recruiter');
        Route::get('applications/{application}/history', [ApplicationController::class, 'history']);

        // Interviews Management
        Route::post('applications/{application}/interviews', [InterviewController::class, 'store'])->middleware('role:admin,recruiter');
        Route::get('interviews', [InterviewController::class, 'index']);
        Route::get('interviews/{interview}', [InterviewController::class, 'show']);
        Route::put('interviews/{interview}', [InterviewController::class, 'update'])->middleware('role:admin,recruiter');
        Route::patch('interviews/{interview}/cancel', [InterviewController::class, 'cancel'])->middleware('role:admin,recruiter');

        // Technical Tasks Management
        Route::post('applications/{application}/tasks', [TechnicalTaskController::class, 'store'])->middleware('role:admin,recruiter');
        Route::get('tasks', [TechnicalTaskController::class, 'index']);
        Route::get('tasks/{task}', [TechnicalTaskController::class, 'show']);
        Route::patch('tasks/{task}/status', [TechnicalTaskController::class, 'updateStatus']);
        Route::post('tasks/{task}/submit', [TechnicalTaskController::class, 'submit'])->middleware('role:candidate');
        Route::get('tasks/{task}/submissions', [TechnicalTaskController::class, 'submissions']);
        Route::patch('submissions/{submission}/review', [TechnicalTaskController::class, 'review'])->middleware('role:admin,recruiter');

        // Dashboard Analytics
        Route::prefix('dashboard')->middleware('role:admin,recruiter,candidate')->group(function () {
            Route::get('overview', [DashboardController::class, 'overview']);
            Route::get('pipeline', [DashboardController::class, 'pipeline']);
            Route::get('interviews', [DashboardController::class, 'interviews']);
            Route::get('monthly-activity', [DashboardController::class, 'monthlyActivity']);
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::patch('{id}/read', [NotificationController::class, 'markAsRead']);
            Route::post('read-all', [NotificationController::class, 'markAllAsRead']);
        });
    });
});
