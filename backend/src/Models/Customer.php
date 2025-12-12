<?php

declare(strict_types=1);

namespace App\Models;

final class Customer
{
    private ?string $id;
    private ?string $name;
    private ?string $cpf;
    private ?string $birthDate;
    private ?string $email;
    private ?string $rg;
    private ?string $phone;
    private ?array $addresses;
    private array $partialFields;

    public function __construct(
        ?string $id,
        ?string $name,
        ?string $cpf,
        ?string $birthDate,
        ?string $email,
        ?string $rg = null,
        ?string $phone = null,
        ?array $addresses = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->cpf = $cpf;
        $this->birthDate = $birthDate;
        $this->email = $email;
        $this->rg = $rg;
        $this->phone = $phone;
        $this->addresses = $addresses;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? null,
            $data['cpf'] ?? null,
            $data['birthDate'] ?? null,
            $data['email'] ?? null,
            $data['rg'] ?? null,
            $data['phone'] ?? null,
            $data['addresses'] ?? []
        );
    }


    public function setPartialFields(array $partialFields): void
    {
        $this->partialFields = $partialFields;
    }

    public static function fromArrayToUpdate(array $data): self
    {
        $instance = self::fromArray($data);
        $instance->setPartialFields(array_keys($data));
        return $instance;
    }

    public function toArray(): array
    {
        $values = [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'birthDate' => $this->birthDate,
            'email' => $this->email,
            'rg' => $this->rg,
            'phone' => $this->phone,
            'addresses' => $this->addresses,
        ];

        if (!empty($this->partialFields)) {
            $values = array_intersect_key(
                $values,
                array_flip($this->partialFields)
            );
        }

        return $values;
    }

    public function setId(string $id): Customer
    {
        $this->id = $id;

        if (!empty($this->partialFields)) {
            $this->partialFields[] = 'id';
        }

        return $this;
    }
}
