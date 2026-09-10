<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technical_task_id')->constrained('technical_tasks')->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->longText('submission_content')->nullable();
            $table->string('submission_url', 500);
            $table->dateTime('submitted_at');
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->string('status', 30)->default('submitted');
            $table->timestamps();

            $table->index('technical_task_id');
            $table->index('candidate_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
