<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;
use App\Database\Database;
use PDO;

abstract class ApiTestCase extends TestCase
{
    protected string $baseUrl = 'http://nginx';
    protected Client $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cleanupDatabase();

        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    private function cleanupDatabase(): void
    {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            $conn->exec("TRUNCATE TABLE customers");
        } catch (Exception $e) {
            var_dump( "Error: " . $e->getMessage());
        }
    }

    protected function request(string $method, string $path, ?array $body = null): array
    {
        $options = [];

        if ($body !== null) {
            $options['json'] = $body;
        }

        try {
            $res = $this->http->request($method, $path, $options);
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }

        $status = $res->getStatusCode();
        $bodyRaw = (string) $res->getBody();
        $decoded = $bodyRaw !== '' ? json_decode($bodyRaw, true) : null;

        return [
            'body' => $decoded,
            'status' => $status,
            'bodyRaw' => $bodyRaw,
            'headers' => $res->getHeaders(),
        ];
    }
}