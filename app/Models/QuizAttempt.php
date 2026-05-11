<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id', 'quiz_id', 'status',
        'started_at', 'submitted_at', 'expires_at',
        'time_taken', 'current_question_index',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'attempt_id');
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class, 'attempt_id');
    }

    // Helpers
    public function getTimeTakenFormattedAttribute(): string
    {
        if (!$this->time_taken) return 'N/A';
        $mins = floor($this->time_taken / 60);
        $secs = $this->time_taken % 60;
        return "{$mins}m {$secs}s";
    }
}
