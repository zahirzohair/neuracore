<?php

namespace Zahirzohair\Neuracore\Domain\User;

interface UserRepository
{
    public function findByEmail(string $email): ?User;
    public function create(User $user): User;
}
