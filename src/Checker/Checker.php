<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker;

use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;

/**
 * @template T of Rule<mixed, mixed>
 */
interface Checker
{

    /**
     * @param mixed $value
     * @param Rule $rule
     * @param ValidationResult $result
     *
     * @psalm-param T $rule
     *
     * @throws NoCheckersRegistered
     */
    public function check($value, Rule $rule, ValidationResult $result): void;

    /**
     * @psalm-return list<class-string<Rule>>
     */
    public function canCheck(): array;
}
