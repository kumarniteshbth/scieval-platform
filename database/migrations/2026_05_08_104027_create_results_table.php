<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->unique()->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Scientific temper sub-scores (0-100)
            $table->decimal('logical_thinking_score', 5, 2)->default(0);
            $table->decimal('evidence_reasoning_score', 5, 2)->default(0);
            $table->decimal('myth_fact_score', 5, 2)->default(0);
            $table->decimal('problem_solving_score', 5, 2)->default(0);
            // Overall evaluation
            $table->enum('overall_level', ['Beginner', 'Developing', 'Proficient', 'Expert'])->default('Beginner');
            $table->text('feedback_text')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
