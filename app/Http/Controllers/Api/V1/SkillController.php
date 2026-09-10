<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use App\Services\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkillController extends Controller
{
    public function __construct(
        protected SkillService $skillService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $skills = $this->skillService->listSkills($request->query('search'));
        return SkillResource::collection($skills);
    }

    public function store(StoreSkillRequest $request): JsonResponse
    {
        $skill = $this->skillService->createSkill($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully.',
            'data' => new SkillResource($skill),
        ], 201);
    }

    public function show(Skill $skill): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new SkillResource($skill),
        ]);
    }

    public function update(UpdateSkillRequest $request, Skill $skill): JsonResponse
    {
        $updated = $this->skillService->updateSkill($skill, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully.',
            'data' => new SkillResource($updated),
        ]);
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $this->authorize('delete', $skill);

        $this->skillService->deleteSkill($skill);

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully.',
        ]);
    }
}
