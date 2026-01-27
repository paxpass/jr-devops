<?php

namespace App\Repositories;

use App\Models\Secret;

interface SecretRepositoryInterface
{
    public function create(string $encryptedContent, ?\DateTime $expiresAt): Secret;
    public function findById(string $id): ?Secret;
    public function delete(string $id): bool;
    public function deleteExpired(): int;
}