<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Events\ApplicationStatusChanged;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplicationPipelineService
{
    /**
     * Transition an application to a target status through state machine validation.
     */
    public function transition(Application $application, ApplicationStatus|string $targetStatus, User $actor, ?string $remarks = null): Application
    {
        $currentStatus = $application->current_status instanceof ApplicationStatus
            ? $application->current_status
            : ApplicationStatus::from($application->current_status);

        $toStatus = $targetStatus instanceof ApplicationStatus
            ? $targetStatus
            : ApplicationStatus::from($targetStatus);

        // 1. Check if same status
        if ($currentStatus === $toStatus) {
            throw ValidationException::withMessages([
                'status' => ["The application is already in the '{$toStatus->value}' stage."],
            ]);
        }

        // 2. Validate transition guard
        if (!$currentStatus->canTransitionTo($toStatus)) {
            $allowed = array_map(fn($s) => $s->value, $currentStatus->allowedTransitions());
            $allowedStr = empty($allowed) ? 'None (Terminal stage)' : implode(', ', $allowed);

            throw ValidationException::withMessages([
                'status' => [
                    "Invalid status transition from '{$currentStatus->value}' to '{$toStatus->value}'. Allowed transitions: [{$allowedStr}]."
                ],
            ]);
        }

        // 3. Perform atomic update + history logging + event dispatch inside transaction
        return DB::transaction(function () use ($application, $currentStatus, $toStatus, $actor, $remarks) {
            $application->update([
                'current_status' => $toStatus->value,
            ]);

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'from_status' => $currentStatus->value,
                'to_status' => $toStatus->value,
                'changed_by' => $actor->id,
                'changed_at' => Carbon::now(),
                'remarks' => $remarks,
            ]);

            // Dispatch event for listener / notifications
            event(new ApplicationStatusChanged(
                $application->load(['job', 'candidate.user']),
                $currentStatus,
                $toStatus,
                $actor,
                $remarks
            ));

            return $application->fresh(['statusHistories.changedByUser', 'job', 'candidate.user']);
        });
    }

    /**
     * Retrieve complete status audit history for an application.
     */
    public function getHistory(Application $application)
    {
        return $application->statusHistories()->with('changedByUser')->get();
    }
}
