<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Combination;

use LeightonThomas\Validation\Rule\Rule;

/**
 * @see https://psalm.dev/docs/annotating_code/type_syntax/union_types/
 *
 * @psalm-template I
 * @psalm-template MyO
 *
 * @extends Rule<I, MyO>
 */
class Union extends Rule
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
    public static function of(Rule $rule): self
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
     * @psalm-return self<I, NewO|MyO>
     */
    public function or(Rule $next): self
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
        $this->messages = [
            self::ERR_MESSAGE => "This value is invalid.",
        ];
    }
}
