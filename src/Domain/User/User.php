<?php

namespace Zahirzohair\Neuracore\Domain\User;

class User
{
    private ?int $id;
    private string $email;
    private string $passwordHash;

    public function __construct(?int $id, string $email, string $passwordHash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->passwordHash);
    }
}
