<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Arrays;

use LeightonThomas\Validation\Rule\Rule;

/**
 * Check that the input is an array with the specified keys and values.
 * Keys that are not defined are not never checked.
 *
 * @psalm-template A of array
 *
 * @extends Rule<mixed, A>
 */
class IsDefinedArray extends Rule
{

    public const ERR_KEY_MISSING = 0;
    public const ERR_NOT_ARRAY = 1;
    public const ERR_UNKNOWN_KEY = 3;

    /**
     * @var ArrayPair[]
     * @psalm-var array<array-key, ArrayPair>
     */
    private array $pairs;

    private bool $allowOtherKeys;

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
        $instance->pairs = [$key => new ArrayPair($value, $key, true)];

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
    public static function ofMaybe($key, Rule $value): self
    {
        /** @psalm-var self<array> $instance */
        $instance = new self();
        $instance->pairs = [$key => new ArrayPair($value, $key, false)];

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
        $this->pairs[$key] = new ArrayPair($value, $key, true);

        return $this;
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
    public function andMaybe($key, Rule $value): self
    {
        $this->pairs[$key] = new ArrayPair($value, $key, false);

        return $this;
    }

    /**
     * @return $this
     */
    public function withNoOtherKeys(): self
    {
        $this->allowOtherKeys = false;

        return $this;
    }

    /**
     * @return ArrayPair[]
     * @psalm-return array<array-key, ArrayPair>
     */
    public function getPairs(): array
    {
        return $this->pairs;
    }

    public function shouldAllowOtherKeys(): bool
    {
        return $this->allowOtherKeys;
    }

    private function __construct()
    {
        $this->pairs = [];
        $this->allowOtherKeys = true;
        $this->messages = [
            self::ERR_KEY_MISSING => 'This key is missing.',
            self::ERR_NOT_ARRAY => 'This value must be an array.',
            self::ERR_UNKNOWN_KEY => 'This key is invalid.',
        ];
    }
}
