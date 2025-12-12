<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private PDO $connection;

    public function __construct()
    {
        $dsn = "mysql:host=" . getenv('DB_HOST') . ":" . getenv('DB_PORT') . ";dbname=" . getenv('DB_NAME');
        $user = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        try {
            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to connect to database: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
