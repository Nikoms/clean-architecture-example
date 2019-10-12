<?php declare(strict_types=1);

namespace Seat\SharedKernel\Service;

class NativePasswordHasher implements PasswordHasher
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordValid(string $hashedPassword, string $password): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
