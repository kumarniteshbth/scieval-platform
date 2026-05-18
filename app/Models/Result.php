<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'attempt_id', 'user_id',
        // Core score fields (added via update migration)
        'score', 'total_questions', 'score_percentage',
        'passed', 'time_taken', 'literacy_level', 'feedback',
        // Scientific temper sub-scores
        'logical_thinking_score', 'evidence_reasoning_score',
        'myth_vs_fact_score', 'problem_solving_score',
        // Legacy/other fields
        'myth_fact_score', 'overall_level', 'feedback_text', 'recommendations',
    ];

    protected $casts = [
        'passed'      => 'boolean',
        'time_taken'  => 'integer',
        'score'       => 'integer',
        'total_questions' => 'integer',
        'score_percentage' => 'integer',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLevelColorAttribute(): string
    {
        return match($this->overall_level) {
            'Expert'     => '#a855f7',
            'Proficient' => '#3b82f6',
            'Developing' => '#eab308',
            default      => '#6b7280',
        };
    }

    public function getLevelBadgeAttribute(): string
    {
        return match($this->overall_level) {
            'Expert'     => 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
            'Proficient' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
            'Developing' => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
            default      => 'bg-gray-500/20 text-gray-400 border border-gray-500/30',
        };
    }
}
