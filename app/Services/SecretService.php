<?php

namespace App\Services;

use App\Repositories\SecretRepositoryInterface;
use Illuminate\Support\Facades\Crypt;

class SecretService
{
    public function __construct(
        private SecretRepositoryInterface $repository
    ) {}

    public function createSecret(string $content, ?int $ttlMinutes = null): array
    {
        $encrypted = Crypt::encryptString($content);
        $expiresAt = $ttlMinutes 
            ? now()->addMinutes($ttlMinutes) 
            : null;

        $secret = $this->repository->create($encrypted, $expiresAt);

        return [
            'id' => $secret->id,
            'url' => url("/api/v1/secrets/{$secret->id}"),
            'expires_at' => $secret->expires_at?->toIso8601String(),
        ];
    }

    public function retrieveAndBurn(string $id): ?string
    {
        $secret = $this->repository->findById($id);

        if (!$secret) {
            return null;
        }

        $decrypted = Crypt::decryptString($secret->encrypted_content);
        $this->repository->delete($id);

        return $decrypted;
    }

    public function cleanupExpired(): int
    {
        return $this->repository->deleteExpired();
    }
}