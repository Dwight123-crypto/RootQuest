<?php

namespace App\DTOs;

readonly class HintUnlockResult
{
    public function __construct(
        public bool $success,
        public string $message,
        public ?string $hintContent = null,
        public int $pointsDeducted = 0,
    ) {}

    public static function unlocked(string $content, int $cost): self
    {
        return new self(
            success: true,
            message: 'Hint unlocked! ' . $cost . ' points deducted.',
            hintContent: $content,
            pointsDeducted: $cost,
        );
    }

    public static function alreadyUnlocked(string $content): self
    {
        return new self(
            success: true,
            message: 'You have already unlocked this hint.',
            hintContent: $content,
        );
    }

    public static function insufficientPoints(int $cost, int $currentScore): self
    {
        return new self(
            success: false,
            message: "You need {$cost} points to unlock this hint, but you only have {$currentScore} points.",
        );
    }
}
