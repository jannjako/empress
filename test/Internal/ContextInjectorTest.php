<?php

declare(strict_types=1);

namespace Empress\Test\Internal;

use Amp\Http\Status;

use Empress\Context;
use Empress\Internal\ContextInjector;
use Empress\Test\Helper\StubRequestTrait;
use Empress\Validation\Registry\ValidatorRegistryInterface;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

final class ContextInjectorTest extends TestCase
{
    use StubRequestTrait;

    public function testInjectorWithExistingResponse(): Generator
    {
        $request = $this->createStubRequest();
        $validatorRegistry = $this->createMock(ValidatorRegistryInterface::class);
        $context = new Context($request, $validatorRegistry);
        $injector = new ContextInjector($context);

        yield $injector->inject(function (Context $ctx): void {
            $ctx
                ->status(Status::NOT_FOUND)
                ->response('Hello');
        });

        self::assertSame(Status::NOT_FOUND, $injector->getResponse()->getStatus());
        self::assertSame('Hello', yield $injector->getResponse()->getBody()->read());
    }

    public function testInjectorWithException(): Generator
    {
        $this->expectException(Exception::class);

        $request = $this->createStubRequest();
        $validatorRegistry = $this->createMock(ValidatorRegistryInterface::class);
        $context = new Context($request, $validatorRegistry);
        $injector = new ContextInjector($context);

        yield $injector->inject(fn () => throw new Exception());
    }
}
