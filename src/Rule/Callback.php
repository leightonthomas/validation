<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule;

use LeightonThomas\Validation\ValidatorFactory;
use LeightonThomas\Validation\ValidationResult;

/**
 * Check if the input matches the defined callback.
 *
 * @template I
 * @template O
 *
 * @extends Rule<I, O>
 */
class Callback extends Rule
{

    /**
     * @var callable
     * @psalm-var callable(I,ValidationResult,ValidatorFactory):void
     */
    private $callback;

    /**
     * @param callable $callback
     * @psalm-param callable(I,ValidationResult,ValidatorFactory):void $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
        $this->messages = [];
    }

    /**
     * @return callable
     * @psalm-return callable(I,ValidationResult,ValidatorFactory):void
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function getMessages(): array
    {
        return [];
    }
}
