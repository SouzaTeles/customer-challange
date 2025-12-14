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

        $customer->setId($this->db->lastInsertId());
        return $customer;
    }

    public function update(Customer $customer): ?array
    {
        $data = $customer->toArray();

        $sets = [];
        foreach (array_keys($data) as $key) {
            $sets[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";

        $this->executeQuery($sql, $data);
        return $this->findById($data['id']);
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
