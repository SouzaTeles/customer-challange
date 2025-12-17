<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Customer;
use App\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

final class CustomerValidationUTest extends TestCase
{
    public function testValidateWithValidData(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
        ]);

        $customer->validate();
        $this->assertTrue(true);
    }

    public function testValidateThrowsExceptionWhenNameIsEmpty(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => '',
            'cpf' => '529.982.247-25',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
        ]);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Dados inválidos');
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('name', $errors);
            $this->assertEquals('Nome é obrigatório', $errors['name']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenCpfIsEmpty(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('cpf', $errors);
            $this->assertEquals('CPF é obrigatório', $errors['cpf']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenCpfIsInvalid(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '12345678900',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('cpf', $errors);
            $this->assertEquals('CPF inválido', $errors['cpf']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenEmailIsEmpty(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '1990-05-15',
            'email' => '',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('email', $errors);
            $this->assertEquals('Email é obrigatório', $errors['email']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenEmailIsInvalid(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '1990-05-15',
            'email' => 'invalid-email',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('email', $errors);
            $this->assertEquals('Email inválido', $errors['email']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenBirthDateIsEmpty(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '',
            'email' => 'joao@example.com',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('birthDate', $errors);
            $this->assertEquals('Data de nascimento é obrigatória', $errors['birthDate']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenBirthDateIsInvalid(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '2024-13-01',
            'email' => 'joao@example.com',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('birthDate', $errors);
            $this->assertEquals('Data de nascimento inválida', $errors['birthDate']);
            throw $e;
        }
    }

    public function testValidateThrowsExceptionWhenPhoneIsInvalid(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
            'phone' => '123',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('phone', $errors);
            $this->assertEquals('Telefone inválido', $errors['phone']);
            throw $e;
        }
    }

    public function testValidateWithValidPhone(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '529.982.247-25',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
            'phone' => '11987654321',
        ]);

        $customer->validate();
        $this->assertTrue(true);
    }

    public function testValidateThrowsExceptionWithMultipleErrors(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => '',
            'cpf' => '',
            'birthDate' => '',
            'email' => '',
        ]);

        $this->expectException(ValidationException::class);
        
        try {
            $customer->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('name', $errors);
            $this->assertArrayHasKey('cpf', $errors);
            $this->assertArrayHasKey('birthDate', $errors);
            $this->assertArrayHasKey('email', $errors);
            $this->assertCount(4, $errors);
            throw $e;
        }
    }
}
