<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule;

/**
 * Check if the input === the provided value (which can be anything).
 *
 * @template O
 *
 * @extends Rule<mixed, O>
 */
class StrictEquals extends Rule
{

    public const ERR_INVALID = 0;

    /**
     * @var mixed
     * @psalm-var O
     */
    private $value;

    /**
     * @param mixed $value
     * @psalm-param O $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->messages = [
            self::ERR_INVALID => 'This value is invalid.',
        ];
    }

    /**
     * @return mixed
     * @psalm-return O
     */
    public function getValue()
    {
        return $this->value;
    }
}
