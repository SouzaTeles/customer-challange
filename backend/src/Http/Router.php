<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\NotFoundException;
use App\Routes\CustomersRoutes;

final class Router
{
    private const PREFIX_PATTERN = '#^/api/#';
    private function extractParams(string $uri)
    {
        $parsed = parse_url($uri, PHP_URL_QUERY);
        if ($parsed === null) {
            return '';
        }
        return $parsed;

    }

    private function extractPath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        return preg_replace(self::PREFIX_PATTERN, '/', $path);
    }

    private function extractSegments(string $path): array
    {
        return explode('/', trim($path, '/'));
    }

    // Essa parte de request poderia estar num arquivo separado
    private function getRequest(array $server)
    {
        $path = $this->extractPath($server['REQUEST_URI']);
        return [
            'uri' => $server['REQUEST_URI'],
            'path' => $path,
            'method' => $server['REQUEST_METHOD'],
            'params' => $this->extractParams($server['REQUEST_URI']),
            'segments' => $this->extractSegments($path)
        ];
    }

    public function handle(array $server): void
    {
        $request = $this->getRequest($server);

        switch ($request['segments'][0]) {
            case 'customers':
                //depois substituir por uma factory
                $customersRoutes = new CustomersRoutes();
                $customersRoutes->handleCustomersRoute($request);
                $this->handleResponse(200, );
                break;
            default:
                throw new NotFoundException();
        }
    }

    private function handleResponse(int $status, ?array $body = null)
    {
        http_response_code($status);
        $response = json_encode($body);
        if (json_last_error() == JSON_ERROR_NONE) {
            header('Content-Type: application/json');
        }
        echo $response;
    }
}
