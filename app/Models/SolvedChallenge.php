<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SolvedChallenge extends Pivot
{
    protected $table = 'solved_challenges';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'solved_at',
    ];

    protected function casts(): array
    {
        return [
            'solved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }
}
