<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->restrictOnDelete();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->foreignId('resume_id')->constrained('resumes')->restrictOnDelete();
            $table->decimal('score', 5, 2)->default(0.00);
            $table->string('current_status', 40)->default('applied');
            $table->dateTime('applied_at');
            $table->timestamps();

            $table->unique(['job_id', 'candidate_id']);
            $table->index('job_id');
            $table->index('candidate_id');
            $table->index('current_status');
            $table->index('score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
