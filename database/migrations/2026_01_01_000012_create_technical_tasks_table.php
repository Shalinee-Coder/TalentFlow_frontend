<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('recruiter_id')->constrained('users')->restrictOnDelete();
            $table->string('title');
            $table->longText('description');
            $table->longText('instructions')->nullable();
            $table->dateTime('assigned_at');
            $table->dateTime('due_at');
            $table->string('status', 30)->default('pending');
            $table->dateTime('reminder_sent_at')->nullable();
            $table->timestamps();

            $table->index('application_id');
            $table->index('recruiter_id');
            $table->index('due_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_tasks');
    }
};
