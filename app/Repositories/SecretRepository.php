<?php

namespace App\Repositories;

use App\Models\Secret;
use Carbon\Carbon;

class SecretRepository implements SecretRepositoryInterface
{
    public function create(string $encryptedContent, ?\DateTime $expiresAt): Secret
    {
        return Secret::create([
            'encrypted_content' => $encryptedContent,
            'expires_at' => $expiresAt,
        ]);
    }

    public function findById(string $id): ?Secret
    {
        return Secret::where('id', $id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->first();
    }

    public function delete(string $id): bool
    {
        return Secret::where('id', $id)->delete() > 0;
    }

    public function deleteExpired(): int
    {
        return Secret::where('expires_at', '<=', Carbon::now())->delete();
    }
}