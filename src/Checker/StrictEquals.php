<?php

declare(strict_types=1);

namespace Validation\Checker;

use Validation\Rule\Rule;
use Validation\Rule\StrictEquals as StrictEqualsRule;
use Validation\ValidationResult;

/**
 * @implements Checker<StrictEqualsRule>
 */
class StrictEquals implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @psalm-param StrictEqualsRule $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if ($value === $rule->getValue()) {
            return;
        }

        $result->addError($rule->getMessages()[StrictEqualsRule::ERR_INVALID]);
    }

    public function canCheck(): array
    {
        return [
            StrictEqualsRule::class,
        ];
    }
}
