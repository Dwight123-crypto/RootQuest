<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserHint extends Pivot
{
    protected $table = 'user_hints';

    protected $fillable = [
        'user_id',
        'hint_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hint(): BelongsTo
    {
        return $this->belongsTo(Hint::class);
    }
}
