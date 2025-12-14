<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    private int $statusCode;
    private ?array $body;
    private array $headers;

    public function __construct(int $statusCode = 200, ?array $body = null, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        if ($this->body !== null) {
            $json = json_encode($this->body);
            if (json_last_error() === JSON_ERROR_NONE) {
                header('Content-Type: application/json');
                echo $json;
            }
        }
    }

    public static function json(array $data, int $statusCode = 200): self
    {
        return new self($statusCode, $data, ['Content-Type' => 'application/json']);
    }

    public static function badRequest(string $errorMessage): self
    {
        return new self(400, ['error' => $errorMessage]);
    }

    public static function unauthorized(string $errorMessage): self
    {
        return new self(401, ['error' => $errorMessage]);
    }

    public static function notFound(): self
    {
        return new self(404);
    }

    public static function created(array $data = []): self
    {
        return new self(201, $data);
    }

    public static function noContent(): self
    {
        return new self(204);
    }

    public static function serverError(): self
    {
        return new self(500);
    }
}

