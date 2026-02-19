<?php

namespace Zahirzohair\Neuracore\Application\Auth;

use Zahirzohair\Neuracore\Domain\User\UserRepository;

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
}
