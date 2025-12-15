<?php

declare(strict_types=1);

namespace App\Models;

final class Address
{
    public function __construct(
        private ?int $id,
        private int $customerId,
        private string $street,
        private string $number,
        private ?string $complement,
        private string $neighborhood,
        private string $zipCode,
        private string $city,
        private string $state
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int)$data['id'] : null,
            customerId: (int)($data['customer_id'] ?? 0),
            street: $data['street'],
            number: $data['number'],
            complement: $data['complement'] ?? null,
            neighborhood: $data['neighborhood'],
            zipCode: $data['zipCode'],
            city: $data['city'],
            state: $data['state']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'zipCode' => $this->zipCode,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }
}
