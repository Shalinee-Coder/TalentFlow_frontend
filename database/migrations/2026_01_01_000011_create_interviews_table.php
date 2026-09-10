<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('interviewer_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('scheduled_at');
            $table->unsignedInteger('duration'); // in minutes
            $table->string('meeting_link', 500)->nullable();
            $table->string('status', 30)->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('application_id');
            $table->index('interviewer_id');
            $table->index('scheduled_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
