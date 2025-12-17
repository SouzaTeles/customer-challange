<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Customer;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use PHPUnit\Framework\TestCase;

final class CustomerServiceUTest extends TestCase
{
    private CustomerRepository $mockCustomerRepo;
    private AddressRepository $mockAddressRepo;
    private CustomerService $service;

    protected function setUp(): void
    {
        $this->mockCustomerRepo = $this->createMock(CustomerRepository::class);
        $this->mockAddressRepo = $this->createMock(AddressRepository::class);
        $this->service = new CustomerService(
            $this->mockCustomerRepo,
            $this->mockAddressRepo
        );
    }

    public function testSearchCallsRepositoryAndConvertsToArrays(): void
    {
        $customer1 = Customer::fromArray([
            'id' => 1,
            'name' => 'João Silva',
            'cpf' => '12345678901',
            'birthDate' => '1990-05-15',
            'email' => 'joao@example.com',
            'rg' => null,
            'phone' => null,
            'addresses' => [],
        ]);

        $customer2 = Customer::fromArray([
            'id' => 2,
            'name' => 'João Santos',
            'cpf' => '98765432100',
            'birthDate' => '1985-03-20',
            'email' => 'joao.santos@example.com',
            'rg' => null,
            'phone' => null,
            'addresses' => [],
        ]);

        $this->mockCustomerRepo->expects($this->once())
            ->method('search')
            ->with('João')
            ->willReturn([$customer1, $customer2]);

        $results = $this->service->search('João');

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertIsArray($results[0]);
        $this->assertIsArray($results[1]);
        $this->assertSame('João Silva', $results[0]['name']);
        $this->assertSame('João Santos', $results[1]['name']);
    }

    public function testSearchReturnsEmptyArrayWhenNoResults(): void
    {
        $this->mockCustomerRepo->expects($this->once())
            ->method('search')
            ->with('NaoExiste')
            ->willReturn([]);

        $results = $this->service->search('NaoExiste');

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testGetAllCallsRepositoryAndConvertsToArrays(): void
    {
        $customer = Customer::fromArray([
            'id' => 1,
            'name' => 'Test Customer',
            'cpf' => '12345678901',
            'birthDate' => '1990-05-15',
            'email' => 'test@example.com',
            'rg' => null,
            'phone' => null,
            'addresses' => [],
        ]);

        $this->mockCustomerRepo->expects($this->once())
            ->method('findAll')
            ->willReturn([$customer]);

        $results = $this->service->getAll();

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertIsArray($results[0]);
        $this->assertSame('Test Customer', $results[0]['name']);
    }
}
