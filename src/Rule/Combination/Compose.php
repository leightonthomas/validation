<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Combination;

use LeightonThomas\Validation\Rule\Rule;

/**
 * Compose rules together to create a new rule that performs validation of all rules in sequence.
 * For example, if you compose Rule<mixed, int> and Rule<int, positive-int>, the result will be a
 * Rule<mixed, positive-int> and validate from mixed -> int -> positive-int.
 *
 * @psalm-template I
 * @psalm-template MyO
 *
 * @extends Rule<I, MyO>
 */
class Compose extends Rule
{

    public const ERR_MESSAGE = 0;

    /**
     * @var array
     * @psalm-var Rule<mixed, mixed>[]
     */
    private array $rules;

    /**
     * @template NewI
     * @template NewO
     *
     * @param Rule $rule
     *
     * @psalm-param Rule<NewI, NewO> $rule
     *
     * @return self
     * @psalm-return self<NewI, NewO>
     */
    public static function from(Rule $rule): self
    {
        return new self($rule);
    }

    /**
     * @template NewO
     *
     * @param Rule $next
     *
     * @psalm-param Rule<MyO, NewO> $next
     *
     * @return self
     * @psalm-return self<I, NewO>
     */
    public function and(Rule $next): self
    {
        $this->rules[] = $next;

        /** @psalm-var self<I, NewO> $this */

        return $this;
    }

    /**
     * @return Rule[]
     * @psalm-return Rule<mixed, mixed>[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param Rule $initial
     *
     * @psalm-param Rule<I, MyO> $initial
     */
    private function __construct(Rule $initial)
    {
        $this->rules = [$initial];
        $this->messages = [];
    }
}
