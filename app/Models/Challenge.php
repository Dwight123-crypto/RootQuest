<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'flag',
        'points',
        'file_path',
    ];

    protected $hidden = [
        'flag',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function hints(): HasMany
    {
        return $this->hasMany(Hint::class);
    }

    public function solvers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'solved_challenges')
            ->withPivot('solved_at');
    }

    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    public function isSolvedByUser(User $user): bool
    {
        return $this->solvers()->where('user_id', $user->id)->exists();
    }

    public function isSolvedByTeam(User $user): bool
    {
        $teammateIds = $user->getTeammateIds();
        return $this->solvers()->whereIn('user_id', $teammateIds)->exists();
    }

    public function getSolveCountAttribute(): int
    {
        return $this->solvers()->count();
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeOrderedByPoints($query)
    {
        return $query->orderBy('points');
    }
}
