<?php

declare(strict_types=1);

namespace Empress\Test;

use Empress\Application;
use Empress\ConfigurationBuilder;
use Empress\Empress;
use Empress\Exception\StartupException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

final class EmpressTest extends TestCase
{
    public function testBootNoRoutes(): \Generator
    {
        $this->expectException(StartupException::class);

        $app = Application::create(
            1234,
            (new ConfigurationBuilder())
                ->withLogger(new NullLogger())
                ->build()
        );
        $empress = new Empress($app);

        yield $empress->boot();
    }
}
