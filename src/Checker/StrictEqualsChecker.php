<?php

declare(strict_types=1);

namespace Validation\Checker;

use Validation\Rule\Rule;
use Validation\Rule\StrictEquals;
use Validation\ValidationResult;

/**
 * @implements Checker<StrictEquals>
 */
class StrictEqualsChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @psalm-param StrictEquals $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if ($value === $rule->getValue()) {
            return;
        }

        $result->addError($rule->getMessages()[StrictEquals::ERR_INVALID]);
    }

    public function canCheck(): array
    {
        return [
            StrictEquals::class,
        ];
    }
}
