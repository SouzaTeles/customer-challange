<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\ValidationException;
use App\Utils\Validator;

final class Address
{
    private const VALID_STATES = [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
        'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
        'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
    ];

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

    public function validate(): void
    {
        $errors = [];

        if (empty($this->street)) {
            $errors['street'] = 'Rua é obrigatória';
        }

        if (empty($this->number)) {
            $errors['number'] = 'Número é obrigatório';
        }

        if (empty($this->neighborhood)) {
            $errors['neighborhood'] = 'Bairro é obrigatório';
        }

        if (empty($this->zipCode)) {
            $errors['zipCode'] = 'CEP é obrigatório';
        } elseif (!Validator::isValidCep($this->zipCode)) {
            $errors['zipCode'] = 'CEP inválido';
        }

        if (empty($this->city)) {
            $errors['city'] = 'Cidade é obrigatória';
        }

        if (empty($this->state)) {
            $errors['state'] = 'Estado é obrigatório';
        } elseif (!in_array(strtoupper($this->state), self::VALID_STATES, true)) {
            $errors['state'] = 'Estado inválido';
        }

        if (!empty($errors)) {
            throw new ValidationException('Dados inválidos', $errors);
        }
    }
}
