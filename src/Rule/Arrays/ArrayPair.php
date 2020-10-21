<?php

declare(strict_types=1);

namespace Validation\Rule\Arrays;

use Validation\Rule\Rule;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class ArrayPair
{

    /**
     * @var Rule
     * @psalm-var Rule<mixed, mixed>
     */
    public Rule $rule;

    /**
     * @var string|int
     * @psalm-var array-key
     */
    public $key;

    public bool $required;

    /**
     * @param Rule $rule
     * @param string|int $key
     * @param bool $required
     *
     * @psalm-param Rule<mixed, mixed> $rule
     * @psalm-param array-key $key
     */
    public function __construct(Rule $rule, string $key, bool $required)
    {
        $this->rule = $rule;
        $this->key = $key;
        $this->required = $required;
    }
}
