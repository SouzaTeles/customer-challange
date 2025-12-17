<?php

namespace App\Repositories;

use App\Exceptions\CpfAlreadyExistsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Models\Customer;

class CustomerRepository extends Repository
{
    protected string $table = 'customers';

    private const string CONSTRAINT_UNIQUE_CPF = 'uq_customers_cpf';
    private const string CONSTRAINT_UNIQUE_EMAIL = 'uq_customers_email';

    public function __construct($database)
    {
        parent::__construct($database);
    }

    public function create(Customer $customer): Customer
    {
        $data = $customer->toArray();
        
        unset($data['id']);
        unset($data['addresses']);

        $columns = $this->getColumns($data);
        $placeholders = $this->getPlaceholders($data);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->executeQuery($sql, $data);

        $customerId = (int)$this->db->lastInsertId();
        $customer->setId($customerId);

        return $customer;
    }

    public function update(Customer $customer): Customer
    {
        $data = $customer->toArray();
        $id = $data['id'];

        unset($data['addresses'], $data['id']);

        $sets = [];
        foreach (array_keys($data) as $key) {
            $sets[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";
        
        $data['id'] = $id;
        $this->executeQuery($sql, $data);
        
        return $customer;
    }

    public function findById($id): ?Customer
    {
        $row = parent::findById($id);

        if (!$row) {
            return null;
        }

        return Customer::fromArray($row);
    }

    public function findAll(): array
    {
        $rows = parent::findAll();
        
        return array_map(fn($row) => Customer::fromArray($row), $rows);
    }

    public function search(string $term): array
    {
        $searchTerm = "%{$term}%";
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE :term 
                OR email LIKE :term 
                OR cpf LIKE :term
                ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['term' => $searchTerm]);
        $rows = $stmt->fetchAll();
        
        return array_map(fn($row) => Customer::fromArray($row), $rows);
    }

    protected function handleDuplicateEntry(string $message): void
    {
        if (str_contains($message, self::CONSTRAINT_UNIQUE_EMAIL)) {
            throw new EmailAlreadyExistsException();
        }

        if (str_contains($message, self::CONSTRAINT_UNIQUE_CPF)) {
            throw new CpfAlreadyExistsException();
        }
    }
}
