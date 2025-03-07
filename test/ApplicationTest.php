<?php

declare(strict_types=1);

namespace Empress\Test;


use Empress\Application;
use Empress\ConfigurationBuilder;
use Empress\Test\Helper\StubServerTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class ApplicationTest extends TestCase
{
    use StubServerTrait;

    public function testCreate(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $configuration = (new ConfigurationBuilder())
            ->withLogger($logger)
            ->build();

        $app = Application::create(1234, $configuration);

        self::assertSame(1234, $app->getPort());
        self::assertSame($logger, $app->getConfiguration()->getLogger());
    }

    public function testOnServerStartStop(): \Generator
    {
        $s = '';

        $app = Application::create(1234);

        $app
            ->onServerStart(function () use (&$s): void {
                $s .= '#';
            })
            ->onServerStop(function () use (&$s): void {
                $s .= '!';
            });

        $server = $this->getStubServer();
        $server->attach($app);

        yield $server->start();
        yield $server->stop();

        self::assertSame('#!', $s);
    }
}
