<?php

declare(strict_types=1);

namespace Validation\Rule\Arrays;

use Validation\Rule\Rule;

/**
 * @psalm-template A of array
 *
 * @implements Rule<mixed, A>
 */
class IsDefinedArray implements Rule
{

    public const ERR_KEY_MISSING = 0;
    public const ERR_NOT_ARRAY = 1;

    /**
     * @var string[]
     * @psalm-var array<int, string>
     */
    private array $messages;

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

    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     * @psalm-param self::ERR_* $type
     */
    public function setMessage(int $type, string $newMessage): self
    {
        $this->messages[$type] = $newMessage;

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
