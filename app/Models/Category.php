<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class);
    }

    public function scopeWithChallengeCount($query)
    {
        return $query->withCount('challenges');
    }
}
