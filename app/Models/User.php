<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'institution',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function completedAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->where('status', 'completed');
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        // Generate initials avatar
        $initials = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$this->name}&background=6366f1&color=fff&size=128";
    }

    public function getScientificLevelAttribute(): string
    {
        $avg = $this->completedAttempts()->avg('percentage') ?? 0;
        if ($avg >= 85) return 'Expert';
        if ($avg >= 70) return 'Proficient';
        if ($avg >= 50) return 'Developing';
        return 'Beginner';
    }

    public function getTotalPointsAttribute(): int
    {
        return $this->completedAttempts()->sum('total_score');
    }
}
