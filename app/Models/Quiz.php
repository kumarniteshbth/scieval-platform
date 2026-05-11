<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'category_id', 'title', 'description', 'difficulty',
        'time_limit', 'passing_score', 'is_active', 'created_by'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getQuestionsCountAttribute(): int
    {
        return $this->questions()->count();
    }

    public function getMaxScoreAttribute(): int
    {
        return $this->questions()->sum('points');
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            'easy'   => 'text-green-400',
            'medium' => 'text-yellow-400',
            'hard'   => 'text-red-400',
            default  => 'text-gray-400',
        };
    }

    public function getDifficultyBadgeAttribute(): string
    {
        return match($this->difficulty) {
            'easy'   => 'bg-green-500/20 text-green-400 border-green-500/30',
            'medium' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
            'hard'   => 'bg-red-500/20 text-red-400 border-red-500/30',
            default  => 'bg-gray-500/20 text-gray-400',
        };
    }
}
