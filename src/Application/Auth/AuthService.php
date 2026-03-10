<?php

namespace Zahirzohair\Neuracore\Application\Auth;

use Zahirzohair\Neuracore\Domain\User\UserRepository;
use Zahirzohair\Neuracore\Domain\User\User;

class AuthService
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function attempt(string $email, string $password)
    {
        $user = $this->users->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!$user->verifyPassword($password)) {
            return null;
        }

        return $user;
    }

    public function register(string $name, string $email, string $password): ?User
    {
        $existing = $this->users->findByEmail($email);
        if ($existing) {
            return null;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(null, $name, $email, $hash);

        return $this->users->create($user);
    }
}
