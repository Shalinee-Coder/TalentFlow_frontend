<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->string('from_status', 40)->nullable();
            $table->string('to_status', 40);
            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
            $table->dateTime('changed_at');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('application_id');
            $table->index('to_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_histories');
    }
};
