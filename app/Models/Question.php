<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'quiz_id', 'category_id', 'question_text', 'explanation',
        'difficulty', 'points', 'order', 'temper_category'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    public function correctOption(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Option::class)->where('is_correct', true);
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function temperLabel(): string
    {
        return match($this->temper_category) {
            'logical_thinking'   => 'Logical Thinking',
            'evidence_reasoning' => 'Evidence Reasoning',
            'myth_vs_fact'       => 'Myth vs Fact',
            'problem_solving'    => 'Problem Solving',
            default              => 'General',
        };
    }

    public function getTemperLabelAttribute(): string
    {
        return $this->temperLabel();
    }
}
