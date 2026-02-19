<?php

namespace Zahirzohair\Neuracore\Domain\User;

interface UserRepository
{
    public function findByEmail(string $email): ?User;
}
