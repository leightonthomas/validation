<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Strings;

use InvalidArgumentException;
use Validation\Rule\Rule;

/**
 * @extends Rule<string, string>
 */
class Length extends Rule
{

    public const ERR_TOO_SHORT = 0;
    public const ERR_TOO_LONG = 1;
    public const ERR_NOT_STRING = 2;

    private ?int $min;

    private ?int $max;

    /**
     * @param int|null $min
     * @param int|null $max
     *
     * @throws InvalidArgumentException if both $min and $max are NULL.
     */
    public function __construct(
        ?int $min = null,
        ?int $max = null
    ) {
        if (($min === null) && ($max === null)) {
            throw new InvalidArgumentException("You must provide either a minimum or a maximum.");
        }

        $this->min = $min;
        $this->max = $max;

        $this->messages = [
            self::ERR_TOO_SHORT => 'This value must be at least {{ minimum }} character(s) long.',
            self::ERR_TOO_LONG => 'This value must be at most {{ maximum }} character(s) long.',
            self::ERR_NOT_STRING => 'This value must be of type string.',
        ];
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
