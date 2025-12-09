<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected string $baseUrl = 'http://nginx';
    protected Client $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
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