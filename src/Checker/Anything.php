<?php

declare(strict_types=1);

namespace Validation\Checker;

use Validation\Rule\Rule;
use Validation\Rule\Anything as AnythingRule;
use Validation\ValidationResult;

/**
 * @implements Checker<AnythingRule>
 */
class Anything implements Checker
{

    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void
    {
    }

    public function canCheck(): array
    {
        return [
            AnythingRule::class,
        ];
    }
}
