<?php

declare(strict_types=1);

namespace Empress\Test;

use Amp\Http\Server\Response;

use Empress\DefaultErrorHandler;
use PHPUnit\Framework\TestCase;

final class DefaultErrorHandlerTest extends TestCase
{
    public function testHandleError(): \Generator
    {
        $errorHandler = new DefaultErrorHandler();

        /** @var Response $response */
        $response = yield $errorHandler->handleError(500);

        self::assertSame(500, $response->getStatus());
    }
}
