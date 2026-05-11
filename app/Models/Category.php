<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'color', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function activeQuizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->where('is_active', true);
    }
}
