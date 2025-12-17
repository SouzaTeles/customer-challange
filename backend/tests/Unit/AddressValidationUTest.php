<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Address;
use App\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

final class AddressValidationUTest extends TestCase
{
    public function testValidateWithValidData(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'complement' => 'Apto 45',
            'neighborhood' => 'Centro',
            'zipCode' => '12345-678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $address->validate();
        $this->assertTrue(true);
    }

    public function testValidateWithValidDataWithoutComplement(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $address->validate();
        $this->assertTrue(true);
    }

    public function testValidateThrowsExceptionWhenStreetIsEmpty(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => '',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('street', $errors);
            $this->assertEquals('Rua é obrigatória', $errors['street']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenNumberIsEmpty(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('number', $errors);
            $this->assertEquals('Número é obrigatório', $errors['number']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenNeighborhoodIsEmpty(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => '',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('neighborhood', $errors);
            $this->assertEquals('Bairro é obrigatório', $errors['neighborhood']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenZipCodeIsEmpty(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('zipCode', $errors);
            $this->assertEquals('CEP é obrigatório', $errors['zipCode']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenZipCodeIsInvalid(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '123',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('zipCode', $errors);
            $this->assertEquals('CEP inválido', $errors['zipCode']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenCityIsEmpty(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => '',
            'state' => 'SP',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('city', $errors);
            $this->assertEquals('Cidade é obrigatória', $errors['city']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenStateIsEmpty(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => '',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('state', $errors);
            $this->assertEquals('Estado é obrigatório', $errors['state']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenStateIsInvalid(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => 'XX',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('state', $errors);
            $this->assertEquals('Estado inválido', $errors['state']);
            throw $e;
        }
    }

    public function testValidateAcceptsLowercaseState(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => 'Rua das Flores',
            'number' => '123',
            'neighborhood' => 'Centro',
            'zipCode' => '12345678',
            'city' => 'São Paulo',
            'state' => 'sp',
        ]);

        $address->validate();
        $this->assertTrue(true);
    }

    public function testValidateAcceptsAllValidStates(): void
    {
        $validStates = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];

        foreach ($validStates as $state) {
            $address = Address::fromArray([
                'id' => 1,
                'customer_id' => 1,
                'street' => 'Rua das Flores',
                'number' => '123',
                'neighborhood' => 'Centro',
                'zipCode' => '12345678',
                'city' => 'São Paulo',
                'state' => $state,
            ]);

            $address->validate();
        }

        $this->assertTrue(true);
    }

    public function testValidateThrowsExceptionWithMultipleErrors(): void
    {
        $address = Address::fromArray([
            'id' => 1,
            'customer_id' => 1,
            'street' => '',
            'number' => '',
            'neighborhood' => '',
            'zipCode' => '',
            'city' => '',
            'state' => '',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $address->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('street', $errors);
            $this->assertArrayHasKey('number', $errors);
            $this->assertArrayHasKey('neighborhood', $errors);
            $this->assertArrayHasKey('zipCode', $errors);
            $this->assertArrayHasKey('city', $errors);
            $this->assertArrayHasKey('state', $errors);
            $this->assertCount(6, $errors);
            throw $e;
        }
    }
}
