<?php

declare(strict_types=1);

namespace Validation\Checker;

use Validation\Rule\Anything;
use Validation\Rule\Rule;
use Validation\ValidationResult;

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
