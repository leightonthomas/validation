<?php

declare(strict_types=1);

namespace Validation\Checker;

use Validation\Rule\Rule;
use Validation\Exception\NoCheckersRegistered;
use Validation\ValidationResult;

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
