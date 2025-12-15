<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Address;
use PDO;

class AddressRepository extends Repository
{
    protected string $table = 'addresses';

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public function create(Address $address): Address
    {
        $data = $address->toArray();
        unset($data['id']);

        $columns = $this->getColumns($data);
        $placeholders = $this->getPlaceholders($data);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $this->executeQuery($sql, $data);

        $address->setId((int)$this->db->lastInsertId());
        
        return $address;
    }

    public function findByCustomerId(int $customerId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE customer_id = :customer_id";
        $stmt = $this->executeQuery($sql, ['customer_id' => $customerId]);
        
        $addresses = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $addresses[] = Address::fromArray($row);
        }

        return $addresses;
    }

    public function deleteByCustomerId(int $customerId): void
    {
        $sql = "DELETE FROM {$this->table} WHERE customer_id = :customer_id";
        $this->executeQuery($sql, ['customer_id' => $customerId]);
    }
}
