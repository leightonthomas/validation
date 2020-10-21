<?php

declare(strict_types=1);

namespace LeightonThomas\Validation;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Rule;

/**
 * @psalm-template I
 * @psalm-template O
 */
class Validator
{

    /**
     * @var Rule
     * @psalm-var Rule<I, O>
     */
    private Rule $rule;

    /**
     * @var Checker[]
     * @var list<Checker>
     */
    private array $checkers;

    /**
     * @param Rule $rule
     * @param Checker[] $checkers
     *
     * @psalm-param Rule<I, O> $rule
     * @psalm-param list<Checker> $checkers
     */
    public function __construct(
        Rule $rule,
        array $checkers
    ) {
        $this->rule = $rule;
        $this->checkers = $checkers;
    }

    /**
     * @param mixed $input
     * @psalm-param I $input
     *
     * @return ValidationResult
     * @psalm-return ValidationResult<O>
     *
     * @throws NoCheckersRegistered
     */
    public function validate($input): ValidationResult
    {
        /** @psalm-var ValidationResult<O> $result */
        $result = new ValidationResult($input);

        foreach ($this->checkers as $checker) {
            $checker->check($input, $this->rule, $result);
        }

        return $result;
    }
}
