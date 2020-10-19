<?php

declare(strict_types=1);

namespace Validation\Checker\Object;

use Validation\Checker\Checker;
use Validation\Rule\Object\IsInstanceOf as IsInstanceOfRule;
use Validation\Rule\Rule;
use Validation\ValidationResult;

use function is_a;
use function is_object;
use function preg_replace;

/**
 * @implements Checker<IsInstanceOfRule>
 */
class IsInstanceOf implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param IsInstanceOfRule $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_object($value)) {
            $result->addError($rule->getMessages()[IsInstanceOfRule::ERR_NOT_OBJECT]);

            return;
        }

        if (is_a($value, $rule->getFqcn(), true)) {
            return;
        }

        $message = $rule->getMessages()[IsInstanceOfRule::ERR_NOT_INSTANCE];

        $result->addError(preg_replace('/{{\s+expected\s+}}/', $rule->getFqcn(), $message));
    }

    public function canCheck(): array
    {
        return [
            IsInstanceOfRule::class,
        ];
    }
}
