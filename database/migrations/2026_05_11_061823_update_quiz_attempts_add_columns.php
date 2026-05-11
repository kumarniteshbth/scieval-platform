<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('completed_at');
            $table->timestamp('expires_at')->nullable()->after('submitted_at');
            $table->integer('current_question_index')->default(0)->after('current_question');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['submitted_at', 'expires_at', 'current_question_index']);
        });
    }
};
