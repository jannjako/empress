<?php

declare(strict_types=1);

namespace Empress\Test\Middleware;

use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;

use Empress\Middleware\DefaultHeadersMiddleware;
use Empress\Test\Helper\StubRequestTrait;
use Generator;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;

final class DefaultHeadersMiddlewareTest extends TestCase
{
    use StubRequestTrait;

    public function testHandleRequest(): Generator
    {
        $headers = [
            'x-Custom-1' => 'some value',
            'x-Custom-2' => 'some other value',
        ];

        $request = $this->createStubRequest();
        $handler = new CallableRequestHandler(fn () => new Response());
        $middleware = new DefaultHeadersMiddleware($headers);

        /** @var Response $response */
        $response = yield $middleware->handleRequest($request, $handler);

        foreach ($headers as $name => $value) {
            self::assertNotContains($name, $response->getHeaders());
            self::assertSame($value, $response->getHeader($name));
        }
    }
}
