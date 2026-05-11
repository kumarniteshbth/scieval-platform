<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->text('explanation')->nullable(); // Shown after answering
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('points')->default(10);
            $table->integer('order')->default(0);
            // Scientific temper tags for scoring
            $table->enum('temper_category', [
                'logical_thinking',
                'evidence_reasoning',
                'myth_fact',
                'problem_solving'
            ])->default('logical_thinking');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
