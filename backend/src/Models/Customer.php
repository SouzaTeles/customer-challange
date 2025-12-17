<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Address;
use App\Exceptions\ValidationException;
use App\Utils\Validator;

final class Customer
{
    private ?int $id;
    private ?string $name;
    private ?string $cpf;
    private ?string $birthDate;
    private ?string $email;
    private ?string $rg;
    private ?string $phone;
    /** @var Address[] */
    private array $addresses;

    public function __construct(
        ?int $id,
        ?string $name,
        ?string $cpf,
        ?string $birthDate,
        ?string $email,
        ?string $rg = null,
        ?string $phone = null,
        array $addresses = []
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
        $addresses = [];
        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $addrData) {
                if ($addrData instanceof Address) {
                    $addresses[] = $addrData;
                } else {
                    $addresses[] = Address::fromArray($addrData);
                }
            }
        }

        return new self(
            $data['id'] ?? null,
            $data['name'] ?? null,
            $data['cpf'] ?? null,
            $data['birthDate'] ?? null,
            $data['email'] ?? null,
            $data['rg'] ?? null,
            $data['phone'] ?? null,
            $addresses
        );
    }

    public function toArray(): array
    {
        $addressesArray = array_map(fn(Address $a) => $a->toArray(), $this->addresses);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'birthDate' => $this->birthDate,
            'email' => $this->email,
            'rg' => $this->rg,
            'phone' => $this->phone,
            'addresses' => $addressesArray,
        ];
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * @param Address[] $addresses
     */
    public function setAddresses(array $addresses): self
    {
        $this->addresses = $addresses;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Customer
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function validate(): void
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'Nome é obrigatório';
        }

        if (empty($this->cpf)) {
            $errors['cpf'] = 'CPF é obrigatório';
        } elseif (!Validator::isValidCpf($this->cpf)) {
            $errors['cpf'] = 'CPF inválido';
        }

        if (empty($this->email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!Validator::isValidEmail($this->email)) {
            $errors['email'] = 'Email inválido';
        }

        if (empty($this->birthDate)) {
            $errors['birthDate'] = 'Data de nascimento é obrigatória';
        } elseif (!Validator::isValidDate($this->birthDate)) {
            $errors['birthDate'] = 'Data de nascimento inválida';
        }

        if (!empty($this->phone) && !Validator::isValidPhone($this->phone)) {
            $errors['phone'] = 'Telefone inválido';
        }

        if (!empty($errors)) {
            throw new ValidationException('Dados inválidos', $errors);
        }
    }

}
