<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hint extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'content',
        'cost',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'integer',
        ];
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function unlockedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_hints')
            ->withTimestamps();
    }

    public function isUnlockedBy(User $user): bool
    {
        return $this->unlockedBy()->where('user_id', $user->id)->exists();
    }
}
