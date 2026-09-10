<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('queue_jobs')) {
            Schema::create('queue_jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });

            return;
        }

        Schema::table('queue_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('queue_jobs', 'queue')) {
                $table->string('queue')->default('default')->index();
            }
            if (!Schema::hasColumn('queue_jobs', 'payload')) {
                $table->longText('payload');
            }
            if (!Schema::hasColumn('queue_jobs', 'attempts')) {
                $table->unsignedTinyInteger('attempts')->default(0);
            }
            if (!Schema::hasColumn('queue_jobs', 'reserved_at')) {
                $table->unsignedInteger('reserved_at')->nullable();
            }
            if (!Schema::hasColumn('queue_jobs', 'available_at')) {
                $table->unsignedInteger('available_at')->default(0);
            }
            if (!Schema::hasColumn('queue_jobs', 'created_at')) {
                $table->unsignedInteger('created_at')->default(0);
            }
        });
    }

    public function down(): void
    {
        // Queue columns may contain pending jobs; leave them intact on rollback.
    }
};