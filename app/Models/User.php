<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'team_id',
        'score',
        'is_admin',
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
            'is_admin' => 'boolean',
            'score' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'team_id', 'score', 'is_admin'])
            ->logOnlyDirty();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function solvedChallenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenge::class, 'solved_challenges')
            ->withPivot('solved_at')
            ->orderByPivot('solved_at', 'desc');
    }

    public function unlockedHints(): BelongsToMany
    {
        return $this->belongsToMany(Hint::class, 'user_hints')
            ->withTimestamps();
    }

    public function scopeNonAdmin($query)
    {
        return $query->where('is_admin', false);
    }

    public function scopeRanked($query)
    {
        return $query->nonAdmin()->orderByDesc('score');
    }

    public function hasSolvedChallenge(Challenge $challenge): bool
    {
        return $this->solvedChallenges()->where('challenge_id', $challenge->id)->exists();
    }

    public function hasUnlockedHint(Hint $hint): bool
    {
        return $this->unlockedHints()->where('hint_id', $hint->id)->exists();
    }

    public function getTeammateIds(): array
    {
        if (!$this->team_id) {
            return [$this->id];
        }

        return User::where('team_id', $this->team_id)->pluck('id')->toArray();
    }
}
