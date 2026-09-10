<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id')->constrained('users')->restrictOnDelete();
            $table->string('title');
            $table->string('department', 100);
            $table->longText('description');
            $table->decimal('required_experience', 4, 1)->default(0.0);
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->dateTime('application_deadline');
            $table->string('status', 30)->default('draft');
            $table->timestamps();

            $table->index('recruiter_id');
            $table->index('status');
            $table->index('department');
            $table->index('application_deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
