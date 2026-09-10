<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('phone', 30)->nullable();
            $table->decimal('total_experience', 4, 1)->default(0.0);
            $table->string('highest_education', 100)->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_position')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->index('total_experience');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
