<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Arrays;

use LeightonThomas\Validation\Rule\Rule;

/**
 * @template K of array-key
 * @template V
 *
 * @extends Rule<mixed, array<K, V>>
 */
class IsArray extends Rule
{

    public const ERR_ANY_INVALID_VALUE = 0;
    public const ERR_BAD_KEY = 1;
    public const ERR_NOT_ARRAY = 2;

    /**
     * @var Rule|null
     * @psalm-var Rule<mixed, K>|null
     */
    private ?Rule $keyRule;

    /**
     * @var Rule|null
     * @psalm-var Rule<mixed, V>|null
     */
    private ?Rule $valueRule;

    /**
     * @param Rule|null $keyRule
     * @param Rule|null $valueRule
     *
     * @psalm-param Rule<mixed, K>|null $keyRule
     * @psalm-param Rule<mixed, V>|null $valueRule
     */
    public function __construct(?Rule $keyRule = null, ?Rule $valueRule = null)
    {
        $this->keyRule = $keyRule;
        $this->valueRule = $valueRule;
        $this->messages = [
            self::ERR_NOT_ARRAY => 'This value must be an array.',
            self::ERR_BAD_KEY => 'This key is invalid.',
        ];
    }

    /**
     * @psalm-return Rule<mixed, K>|null
     * @return Rule|null
     */
    public function getKeyRule(): ?Rule
    {
        return $this->keyRule;
    }

    /**
     * @psalm-return Rule<mixed, V>|null
     * @return Rule|null
     */
    public function getValueRule(): ?Rule
    {
        return $this->valueRule;
    }
}
