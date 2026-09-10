<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->string('file_path', 500);
            $table->string('original_filename');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->string('processing_status', 30)->default('uploaded');
            $table->longText('extracted_text')->nullable();
            $table->json('extracted_data')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();

            $table->index('candidate_id');
            $table->index('processing_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
