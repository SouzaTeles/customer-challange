<?php
declare(strict_types=1);

namespace App\Http;

//TODO: depois utilizar ServerRequestInterface
class Request
{
    private const string PREFIX_PATTERN = '#^/api/#';
    private string $uri;
    private array $headers;
    private string $method;
    private array $segments;
    private array $bodyContent;
    private array $queryParams;

    public function __construct($server)
    {
        $path = $this->extractPath($server['REQUEST_URI']);

        $this->uri = $server['REQUEST_URI'];
        $this->method = $server['REQUEST_METHOD'];
        $this->headers = getallheaders() ?: [];
        $this->segments = $this->extractSegments($path);
        $this->bodyContent = $this->extractBodyContent($server['CONTENT_TYPE']);
        $this->queryParams = $this->extractParams($server['REQUEST_URI']);
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getbodyContent(): array
    {
        return $this->bodyContent;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function popSegments(): mixed
    {
        return array_shift($this->segments);
    }

    private function extractParams(string $uri)
    {
        $parsed = parse_url($uri, PHP_URL_QUERY);
        if ($parsed === null) {
            return [];
        }
        return $parsed;

    }

    private function extractBodyContent(string $contentType): array
    {
        if ($contentType == 'application/json') {
            return json_decode(file_get_contents('php://input'), true);
        }

        return [];
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

}
