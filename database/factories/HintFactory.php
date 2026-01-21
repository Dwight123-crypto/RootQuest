<?php

namespace Database\Factories;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;

class HintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'challenge_id' => Challenge::factory(),
            'content' => fake()->sentence(),
            'cost' => fake()->randomElement([25, 50, 75, 100]),
        ];
    }

    public function forChallenge(Challenge $challenge): static
    {
        return $this->state(fn (array $attributes) => [
            'challenge_id' => $challenge->id,
        ]);
    }

    public function withCost(int $cost): static
    {
        return $this->state(fn (array $attributes) => [
            'cost' => $cost,
        ]);
    }
}
