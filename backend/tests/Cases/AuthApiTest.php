<?php

declare(strict_types=1);

namespace Tests\Cases;

use Tests\ApiTestCase;
use App\Database\Database;
use App\Repositories\UserRepository;
use App\Services\UserService;

final class AuthApiTest extends ApiTestCase
{
    private function registerUser(string $name, string $email, string $password): void
    {
        $db = new Database();
        $repo = new UserRepository($db);
        $service = new UserService($repo);

        $service->register([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public function testShouldLoginSuccessfully(): void
    {
        $this->registerUser('Souza Teles', 'teles@example.com', 'secret123');

        $loginPayload = [
            'email' => 'teles@example.com',
            'password' => 'secret123'
        ];

        $response = $this->request('POST', '/api/auth/login', $loginPayload);

        $this->assertSame(200, $response['status']);
        $this->assertEquals('Login realizado com sucesso', $response['body']['message']);
        $this->assertEquals('Souza Teles', $response['body']['user']['name']);

        $this->assertStringContainsString('PHPSESSID', $response['headers']["Set-Cookie"][0]);
    }

    public function testShouldFailLoginWithInvalidEmail(): void
    {
        $this->registerUser('Souza Teles', 'teles@example.com', 'secret123');

        $loginPayload = [
            'email' => 'telesINVALID@example.com',
            'password' => 'secret123'
        ];

        $response = $this->request('POST', '/api/auth/login', $loginPayload);

        $this->assertSame(401, $response['status']);
        $this->assertEquals('Credenciais inválidas', $response['body']['error']);
    }

    public function testShouldFailLoginWithWrongPass(): void
    {
        $this->registerUser('Souza Teles', 'teles@example.com', 'secret123');

        $loginPayload = [
            'email' => 'teles@example.com',
            'password' => 'WRONG-PASSWORD'
        ];

        $response = $this->request('POST', '/api/auth/login', $loginPayload);

        $this->assertSame(401, $response['status']);
        $this->assertEquals('Credenciais inválidas', $response['body']['error']);
    }
}
