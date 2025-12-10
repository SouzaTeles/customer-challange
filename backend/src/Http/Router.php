<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\NotFoundException;

final class Router
{
    public function handle(Request $request): void
    {
        try {
            $route = RouterFactory::create($request->popSegments());
            $data = $route->handle($request);
            $this->handleResponse(200, $data);
        } catch (NotFoundException $e) {
            $this->handleResponse(404);
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
