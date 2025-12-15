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

    public function testApiRoot(): void
    {
        $res = $this->request('GET', '/api/');

        $this->assertSame(200, $res['status']);
    }

    public function testCreateCustomer(): void
    {
        $res = $this->request('POST', '/api/customers/', $this->customer);

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('id', $res['body']);
        $this->assertSame($this->customer['name'], $res['body']['name']);
        $this->assertSame($this->customer['email'], $res['body']['email']);
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
}
