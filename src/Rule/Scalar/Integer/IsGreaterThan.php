<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Integer;

use Validation\Rule\Rule;

/**
 * @extends Rule<int, int>
 */
class IsGreaterThan extends Rule
{

    public const ERR_LESS_THAN = 0;
    public const ERR_IS_EQUAL = 1;
    public const ERR_LESS_THAN_OR_EQUAL = 2;
    public const ERR_NOT_INTEGER = 3;

    private int $value;

    private bool $orEqual;

    public function __construct(int $value)
    {
        $this->value = $value;
        $this->orEqual = false;
        $this->messages = [
            self::ERR_LESS_THAN => 'This value must be greater than {{ value }}.',
            self::ERR_IS_EQUAL => 'This value must be greater than {{ value }}.',
            self::ERR_LESS_THAN_OR_EQUAL => 'This value must be greater than or equal to {{ value }}.',
            self::ERR_NOT_INTEGER => 'This value must be an integer.',
        ];
    }

    public function allowEqual(): self
    {
        $this->orEqual = true;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function shouldAllowEqual(): bool
    {
        return $this->orEqual;
    }
}
