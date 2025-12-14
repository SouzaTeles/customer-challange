<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Exceptions\EmailAlreadyExistsException;
use App\Models\User;
use PDO;

class UserRepository extends Repository
{
    protected string $table = 'users';

    private const string CONSTRAINT_UNIQUE_EMAIL = 'uq_users_email';

    public function __construct(Database $database) 
    {
        parent::__construct($database);
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->executeQuery($sql, ['email' => $email]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return User::fromArray($data);
    }

    public function create(User $user): User
    {
        $data = $user->toArray();
        $data['password'] = $user->getPassword();
        unset($data['id']);

        $columns = $this->getColumns($data);
        $placeholders = $this->getPlaceholders($data);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $this->executeQuery($sql, $data);

        $user->setId((int)$this->db->lastInsertId());
        
        return $user;
    }

    protected function handleDuplicateEntry(string $message): void
    {
        if (str_contains($message, self::CONSTRAINT_UNIQUE_EMAIL)) {
            throw new EmailAlreadyExistsException();
        }
    }
}
