<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Combination;

use LeightonThomas\Validation\Rule\Rule;

/**
 * This rule reflects Psalm's intersection types, but due to limitations only works with objects at the moment.
 * For example, the intersection of Rule<mixed, Foo> and Rule<mixed, Bar> would be Rule<mixed, Foo&Bar>.
 *
 * @see https://psalm.dev/docs/annotating_code/type_syntax/intersection_types/
 *
 * @template I
 * @template O of object
 *
 * @extends Rule<I, O>
 */
class Intersection extends Rule
{

    public const ERR_INVALID = 0;

    /**
     * @var Rule[]
     * @psalm-var Rule<I, mixed>[]
     */
    private array $rules;

    /**
     * @template IncomingI
     * @template IncomingO of object
     *
     * @param Rule $rule
     * @psalm-param Rule<IncomingI, IncomingO> $rule
     *
     * @return self
     * @psalm-return self<IncomingI, IncomingO>
     */
    public static function of(Rule $rule): self
    {
        /** @psalm-var self<IncomingI, IncomingO> $instance */
        $instance = new self($rule);

        return $instance;
    }

    /**
     * @template IncomingO of object
     *
     * @param Rule $rule
     * @psalm-param Rule<I, IncomingO> $rule
     *
     * @return self
     * @psalm-return self<I, O&IncomingO>
     */
    public function and(Rule $rule): self
    {
        $this->rules[] = $rule;

        /** @psalm-var self<I, O&IncomingO> $this */

        return $this;
    }

    /**
     * @return Rule[]
     * @psalm-return Rule<I, mixed>[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    private function __construct(Rule $rule)
    {
        $this->rules = [$rule];
        $this->messages = [];
    }
}
