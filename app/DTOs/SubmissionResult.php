<?php

namespace App\DTOs;

readonly class SubmissionResult
{
    public function __construct(
        public bool $success,
        public string $message,
        public int $pointsAwarded = 0,
    ) {}

    public static function correct(int $points): self
    {
        return new self(
            success: true,
            message: 'Correct flag! You earned ' . $points . ' points.',
            pointsAwarded: $points,
        );
    }

    public static function incorrect(): self
    {
        return new self(
            success: false,
            message: 'Incorrect flag. Try again!',
        );
    }

    public static function alreadySolved(): self
    {
        return new self(
            success: false,
            message: 'This challenge has already been solved by you or your team.',
        );
    }
}
