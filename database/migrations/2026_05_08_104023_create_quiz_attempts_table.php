<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_taken')->nullable(); // seconds
            $table->integer('total_score')->default(0);
            $table->integer('max_score')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('literacy_score', 5, 2)->default(0); // 0-100
            $table->decimal('temper_score', 5, 2)->default(0);  // 0-100
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->integer('current_question')->default(0); // for resuming
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
