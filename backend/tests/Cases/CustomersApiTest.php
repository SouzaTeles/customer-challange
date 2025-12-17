<?php

declare(strict_types=1);

namespace Tests\Cases;

use Tests\ApiTestCase;

final class CustomersApiTest extends ApiTestCase
{
    private $customer = [
        'rg' => 'MG-12.345.678',
        'cpf' => '99986067057',
        'name' => 'Lucas Teles',
        'email' => 'email@example.com',
        'phone' => '21999990000',
        'birthDate' => '1990-05-10',
        'addresses' => [
            [
                'city' => 'Teresópolis',
                'state' => 'RJ',
                'street' => 'Rua A',
                'number' => '123',
                'zipCode' => '25959-456',
                'complement' => 'Apto 101',
                'neighborhood' => 'Centro',
            ],
            [
                'city' => 'Teresópolis',
                'state' => 'MG',
                'street' => 'Rua B',
                'number' => '456',
                'zipCode' => '32123-000',
                'complement' => null,
                'neighborhood' => 'Bairro B',
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate();
    }

    public function testCreateCustomer(): void
    {
        $res = $this->request('POST', '/api/customers/', $this->customer);

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('id', $res['body']);
        $this->assertSame($this->customer['name'], $res['body']['name']);
        $this->assertSame($this->customer['email'], $res['body']['email']);
        
        $this->assertArrayHasKey('addresses', $res['body']);
        $this->assertIsArray($res['body']['addresses']);
        $this->assertCount(2, $res['body']['addresses']);
        
        foreach ($res['body']['addresses'] as $address) {
            $this->assertArrayHasKey('id', $address);
            $this->assertIsInt($address['id']);
        }
    }

    public function testCreateCustomerFailsWhenEmailAlreadyExists(): void
    {
        $this->request('POST', '/api/customers/', $this->customer);

        $res = $this->request('POST', '/api/customers/', $this->customer);

        $this->assertSame(409, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('error', $res['body']);
    }

    public function testListCustomers(): void
    {
        $this->request('POST', '/api/customers/', $this->customer);

        $res = $this->request('GET', '/api/customers/');
        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertNotEmpty($res['body']);
    }

    public function testGetCustomerById(): void
    {
        $create = $this->request('POST', '/api/customers/', $this->customer);
        $id = $create['body']['id'];

        $res = $this->request('GET', '/api/customers/' . $id);

        $this->assertSame(200, $res['status']);
        $this->assertSame($this->customer['name'], $res['body']['name']);
        $this->assertSame($this->customer['email'], $res['body']['email']);
    }

    public function testUpdateCustomer(): void
    {
        $create = $this->request('POST', '/api/customers/', $this->customer);
        $id = $create['body']['id'];

        $updatedData = $this->customer;
        $updatedData['name'] = 'Souza Teles';

        $update = $this->request('PUT', '/api/customers/' . $id, $updatedData);
        $this->assertSame(200, $update['status']);
        $this->assertSame('Souza Teles', $update['body']['name']);
        
        $this->assertArrayHasKey('addresses', $update['body']);
        $this->assertIsArray($update['body']['addresses']);
        $this->assertCount(2, $update['body']['addresses']);
        
        foreach ($update['body']['addresses'] as $address) {
            $this->assertArrayHasKey('id', $address);
            $this->assertIsInt($address['id']);
            $this->assertGreaterThan(0, $address['id']);
        }

        $get = $this->request('GET', '/api/customers/' . $id);
        $this->assertSame('Souza Teles', $get['body']['name']);
    }

    public function testDeleteCustomer(): void
    {
        $create = $this->request('POST', '/api/customers/', $this->customer);
        $id = $create['body']['id'];

        $delete = $this->request('DELETE', '/api/customers/' . $id);
        $this->assertSame(204, $delete['status']);

        $get = $this->request('GET', '/api/customers/' . $id);
        $this->assertSame(404, $get['status']);
    }

    public function testSearchCustomers(): void
    {
        $customer1 = $this->customer;
        $customer1['name'] = 'João Silva';
        $customer1['email'] = 'joao@example.com';
        $customer1['cpf'] = '12345678909';
        $this->request('POST', '/api/customers/', $customer1);

        $customer2 = $this->customer;
        $customer2['name'] = 'Maria Santos';
        $customer2['email'] = 'maria@example.com';
        $customer2['cpf'] = '52998224725';
        $this->request('POST', '/api/customers/', $customer2);

        $searchByName = $this->request('GET', '/api/customers/?search=João');
        $this->assertSame(200, $searchByName['status']);
        $this->assertIsArray($searchByName['body']);
        $this->assertNotEmpty($searchByName['body']);
        $this->assertSame('João Silva', $searchByName['body'][0]['name']);

        $searchByEmail = $this->request('GET', '/api/customers/?search=maria@example');
        $this->assertSame(200, $searchByEmail['status']);
        $this->assertIsArray($searchByEmail['body']);
        $this->assertNotEmpty($searchByEmail['body']);
        $this->assertSame('Maria Santos', $searchByEmail['body'][0]['name']);

        $searchByCpf = $this->request('GET', '/api/customers/?search=123456');
        $this->assertSame(200, $searchByCpf['status']);
        $this->assertIsArray($searchByCpf['body']);
        $this->assertNotEmpty($searchByCpf['body']);
        $this->assertSame('João Silva', $searchByCpf['body'][0]['name']);

        $searchNoResults = $this->request('GET', '/api/customers/?search=NaoExiste');
        $this->assertSame(200, $searchNoResults['status']);
        $this->assertIsArray($searchNoResults['body']);
        $this->assertEmpty($searchNoResults['body']);
    }
}
