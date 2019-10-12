<?php
declare(strict_types=1);

namespace Seat\SharedKernel\Service;

interface PasswordHasher
{
    public function hash(string $password): string;

    public function isPasswordValid(string $hashedPassword, string $password): bool;
}
