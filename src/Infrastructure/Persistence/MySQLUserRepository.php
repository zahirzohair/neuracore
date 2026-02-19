<?php

namespace Zahirzohair\Neuracore\Infrastructure\Persistence;

use PDO;
use Zahirzohair\Neuracore\Domain\User\User;
use Zahirzohair\Neuracore\Domain\User\UserRepository;

class MySQLUserRepository implements UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            (int) $row['id'],
            $row['email'],
            $row['password']
        );
    }
}
