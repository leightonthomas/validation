<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker;

use LeightonThomas\Validation\Rule\Anything;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;

/**
 * @implements Checker<Anything>
 */
class AnythingChecker implements Checker
{

    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
    }

    public function canCheck(): array
    {
        return [
            Anything::class,
        ];
    }
}
