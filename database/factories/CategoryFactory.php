<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    private static array $categories = [
        'Web', 'Crypto', 'Pwn', 'Reverse', 'Forensics', 'Misc', 'OSINT', 'Steganography',
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $name = self::$categories[self::$index % count(self::$categories)];
        self::$index++;

        return [
            'name' => $name,
        ];
    }
}
