<?php

namespace Zahirzohair\Neuracore\Database;

use PDO;

class Connection
{
    public static function make(): PDO
    {
        $host = '127.0.0.1';
        $db   = 'neuracore';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
