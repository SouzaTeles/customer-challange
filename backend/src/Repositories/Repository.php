<?php

namespace App\Repositories;

use PDO;
use PDOException;
use Exception;

abstract class Repository
{
    protected $db;
    protected string $table;

    protected const int ERROR_DUPLICATE_ENTRY = 1062;

    public function __construct($database)
    {
        $this->db = $database->getConnection();
    }

    protected function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $errorInfo = $e->errorInfo;
            if (isset($errorInfo[1]) && $errorInfo[1] === self::ERROR_DUPLICATE_ENTRY) {
                $this->handleDuplicateEntry($errorInfo[2]);
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->executeQuery($sql, ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id])->rowCount() > 0;
    }
}
