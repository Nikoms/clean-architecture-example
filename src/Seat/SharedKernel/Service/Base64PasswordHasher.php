<?php declare(strict_types=1);

namespace Seat\SharedKernel\Service;

class Base64PasswordHasher implements PasswordHasher
{
    public function hash(string $password): string
    {
        return base64_encode($password);
    }

    public function isPasswordValid(string $hashedPassword, string $password): bool
    {
        return base64_decode($hashedPassword) === $password;
    }
}
