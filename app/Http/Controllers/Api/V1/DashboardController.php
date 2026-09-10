<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Get high-level recruitment overview KPIs.
     */
    public function overview(Request $request): JsonResponse
    {
        $period = $request->string('period', 'all')->toString();
        $overview = $this->dashboardService->getOverview($request->user(), $period);

        return response()->json([
            'success' => true,
            'data' => $overview,
        ]);
    }

    /**
     * Get recruitment pipeline stage distribution.
     */
    public function pipeline(Request $request): JsonResponse
    {
        $distribution = $this->dashboardService->getPipelineDistribution($request->user());

        return response()->json([
            'success' => true,
            'data' => $distribution,
        ]);
    }

    /**
     * Get weekly interview schedules.
     */
    public function interviews(Request $request): JsonResponse
    {
        $data = $this->dashboardService->getWeeklyInterviewSchedule($request->user());

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function monthlyActivity(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getMonthlyActivity($request->user()),
        ]);
    }
}
