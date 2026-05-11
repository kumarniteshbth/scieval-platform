<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->integer('score')->default(0)->after('user_id');
            $table->integer('total_questions')->default(0)->after('score');
            $table->integer('score_percentage')->default(0)->after('total_questions');
            $table->boolean('passed')->default(false)->after('score_percentage');
            $table->integer('time_taken')->nullable()->after('passed');
            $table->string('literacy_level')->default('Beginner')->after('time_taken');
            $table->string('myth_vs_fact_score')->default(0)->after('logical_thinking_score');
            $table->text('feedback')->nullable()->after('literacy_level');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['score', 'total_questions', 'score_percentage', 'passed', 'time_taken', 'literacy_level', 'myth_vs_fact_score', 'feedback']);
        });
    }
};
