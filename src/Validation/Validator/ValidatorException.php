<?php

namespace Empress\Validation\Validator;

class ValidatorException extends \Exception
{
    public function __construct(string $message = '', private array $exceptions = [])
    {
        parent::__construct($message);
    }

    /**
     * @param ValidatorException[] $exceptions
     */
    public static function collect(array $exceptions): static
    {
        return new static('Validation errors', $exceptions);
    }

    /**
     * @return static[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
