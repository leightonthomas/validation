<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Scalar\Numeric;

use LeightonThomas\Validation\Rule\Rule;

/**
 * Check if the numeric input is less than (or equal to, if configured) the specified value.
 * Due to limitations at the moment, the type being compared against must be the same as the input type, e.g.
 * Rule<int, int>, Rule<float, float>, Rule<numeric-string, numeric-string>.
 *
 * @see https://psalm.dev/docs/annotating_code/type_syntax/scalar_types/#numeric
 *
 * @template T of numeric
 *
 * @extends Rule<T, T>
 */
class IsLessThan extends Rule
{

    public const ERR_GREATER_THAN = 0;
    public const ERR_IS_EQUAL = 1;
    public const ERR_GREATER_THAN_OR_EQUAL = 2;
    public const ERR_NOT_NUMERIC = 3;

    /**
     * @var float|int|string
     * @psalm-var numeric
     */
    private $value;

    private bool $orEqual;

    /**
     * @param int|float|string $value
     *
     * @psalm-param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->orEqual = false;
        $this->messages = [
            self::ERR_GREATER_THAN => 'This value must be less than {{ value }}.',
            self::ERR_IS_EQUAL => 'This value must be less than {{ value }}.',
            self::ERR_GREATER_THAN_OR_EQUAL => 'This value must be less than or equal to {{ value }}.',
            self::ERR_NOT_NUMERIC => 'This value must be numeric.',
        ];
    }

    /**
     * @return self
     * @psalm-return $this
     */
    public function allowEqual(): self
    {
        $this->orEqual = true;

        return $this;
    }

    /**
     * @return float|int|string
     * @psalm-return numeric
     */
    public function getValue()
    {
        return $this->value;
    }

    public function shouldAllowEqual(): bool
    {
        return $this->orEqual;
    }
}
