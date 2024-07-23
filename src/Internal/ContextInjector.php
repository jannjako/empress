<?php

declare(strict_types=1);

namespace Empress\Internal;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Empress\ContextInterface;
use Throwable;
use function Amp\async;


/**
 * Class ContextInjector.
 *
 * Used for injecting the context object into handlers.
 * The context is injected in such a way that alleviates the need for manually returning a response from handlers.
 *
 * @internal
 */
final class ContextInjector
{
    public function __construct(
        private ContextInterface $context,
        private ?Throwable $exception = null
    ) {
    }

    /**
     * Injects the context object into the handler.
     */
    public function inject(callable $handler): void
    {
        async($handler, $this->context, $this->exception);
    }

    public function getRequest(): Request
    {
        return $this->context->getHttpServerRequest();
    }

    public function getResponse(): Response
    {
        return $this->context->getHttpServerResponse();
    }

    public function getThrowable(): ?Throwable
    {
        return $this->exception;
    }

    public function setThrowable(Throwable $exception): void
    {
        $this->exception = $exception;
    }
}
