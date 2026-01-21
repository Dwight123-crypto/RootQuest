<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    public const MAX_MEMBERS = 4;

    protected $fillable = [
        'name',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getTotalScoreAttribute(): int
    {
        return $this->members()->nonAdmin()->sum('score');
    }

    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getIsFullAttribute(): bool
    {
        return $this->member_count >= self::MAX_MEMBERS;
    }

    public function scopeWithScores($query)
    {
        return $query->withSum(['members as total_score' => function ($q) {
            $q->where('is_admin', false);
        }], 'score');
    }

    public function scopeRanked($query)
    {
        return $query->withScores()->orderByDesc('total_score');
    }
}
