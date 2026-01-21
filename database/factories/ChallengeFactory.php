<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraphs(2, true),
            'flag' => 'FLAG{' . fake()->uuid() . '}',
            'points' => fake()->randomElement([100, 150, 200, 250, 300, 400, 500]),
            'file_path' => null,
        ];
    }

    public function withFile(string $path): static
    {
        return $this->state(fn (array $attributes) => [
            'file_path' => $path,
        ]);
    }

    public function forCategory(Category $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    public function withPoints(int $points): static
    {
        return $this->state(fn (array $attributes) => [
            'points' => $points,
        ]);
    }
}
