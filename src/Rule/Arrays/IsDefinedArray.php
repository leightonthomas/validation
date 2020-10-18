<?php

declare(strict_types=1);

namespace Validation\Rule\Arrays;

use Validation\Rule\Rule;

/**
 * @psalm-template A of array
 *
 * @extends Rule<mixed, A>
 */
class IsDefinedArray extends Rule
{

    public const ERR_KEY_MISSING = 0;
    public const ERR_NOT_ARRAY = 1;

    /**
     * @var Rule[]
     * @psalm-var array<array-key, Rule<mixed, mixed>>
     */
    private array $pairs;

    /**
     * @template NewV
     *
     * @param int|string $key
     * @param Rule $value
     *
     * @psalm-param array-key $key
     * @psalm-param Rule<mixed, NewV> $value
     *
     * @return self
     * @psalm-return self<A>
     */
    public static function of($key, Rule $value): self
    {
        /** @psalm-var self<array> $instance */
        $instance = new self();
        $instance->pairs[$key] = $value;

        return $instance;
    }

    /**
     * @template NewV
     *
     * @param int|string $key
     * @param Rule $value
     *
     * @psalm-param array-key $key
     * @psalm-param Rule<mixed, NewV> $value
     *
     * @return self
     * @psalm-return self<A>
     */
    public function and($key, Rule $value): self
    {
        $this->pairs[$key] = $value;

        return $this;
    }

    /**
     * @return Rule[]
     * @psalm-return array<array-key, Rule<mixed, mixed>>
     */
    public function getPairs(): array
    {
        return $this->pairs;
    }

    private function __construct()
    {
        $this->pairs = [];
        $this->messages = [
            self::ERR_KEY_MISSING => 'This key is missing.',
            self::ERR_NOT_ARRAY => 'This value must be an array.',
        ];
    }
}
