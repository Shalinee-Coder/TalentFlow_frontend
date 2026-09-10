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
        }

        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                foreach (['queue', 'payload', 'attempts', 'reserved_at', 'available_at'] as $column) {
                    if (Schema::hasColumn('jobs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_jobs');
    }
};