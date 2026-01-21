<?php

namespace App\Services;

use App\Models\Challenge;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileUploadService
{
    private const ALLOWED_MIMES = [
        'application/zip',
        'application/x-zip-compressed',
        'text/plain',
        'application/pdf',
        'image/png',
        'image/jpeg',
        'application/octet-stream',
    ];

    private const ALLOWED_EXTENSIONS = [
        'zip', 'txt', 'pdf', 'png', 'jpg', 'jpeg', 'tar', 'gz', 'py', 'c', 'cpp', 'java',
    ];

    private const MAX_SIZE_MB = 10;

    public function uploadChallengeFile(UploadedFile $file): string
    {
        $this->validateFile($file);

        $filename = $this->generateSecureFilename($file);
        $path = $file->storeAs('challenges', $filename, 'local');

        return $path;
    }

    public function downloadChallengeFile(Challenge $challenge): ?StreamedResponse
    {
        if (!$challenge->hasFile()) {
            return null;
        }

        if (!Storage::disk('local')->exists($challenge->file_path)) {
            return null;
        }

        $filename = basename($challenge->file_path);
        $originalName = Str::after($filename, '_');

        return Storage::disk('local')->download($challenge->file_path, $originalName);
    }

    public function deleteFile(string $path): bool
    {
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->delete($path);
        }

        return false;
    }

    private function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed types: ' . implode(', ', self::ALLOWED_EXTENSIONS)
            );
        }

        $maxSizeBytes = self::MAX_SIZE_MB * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            throw new \InvalidArgumentException(
                'File too large. Maximum size: ' . self::MAX_SIZE_MB . 'MB'
            );
        }
    }

    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($originalName);
        $uniqueId = Str::random(16);

        return "{$uniqueId}_{$sanitizedName}.{$extension}";
    }
}
