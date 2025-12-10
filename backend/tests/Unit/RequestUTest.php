<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Request;
use PHPUnit\Framework\TestCase;

final class RequestUTest extends TestCase
{
    public function testRequestParsesUriMethodAndQueryParams(): void
    {
        $server = [
            'REQUEST_URI' => '/api/customers/123?page=2&sort=asc',
            'REQUEST_METHOD' => 'GET',
            'CONTENT_TYPE' => 'application/json',
        ];

        $request = new Request($server);

        $this->assertSame('/api/customers/123?page=2&sort=asc', $request->getUri());
        $this->assertSame('GET', $request->getMethod());

        $queryParams = $request->getQueryParams();
        $this->assertSame([
            'page' => '2',
            'sort' => 'asc',
        ], $queryParams);
    }

    public function testRequestBuildsSegmentsFromApiPath(): void
    {
        $server = [
            'REQUEST_URI' => '/api/customers/123',
            'REQUEST_METHOD' => 'GET',
            'CONTENT_TYPE' => 'text/plain',
        ];

        $request = new Request($server);

        $first = $request->popSegments();
        $second = $request->popSegments();
        $third = $request->popSegments();

        $this->assertSame('customers', $first);
        $this->assertSame('123', $second);
        $this->assertNull($third);
    }
}
