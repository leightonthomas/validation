<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Object;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;

use function is_a;
use function is_object;
use function preg_replace;

/**
 * @implements Checker<IsInstanceOf>
 */
class IsInstanceOfChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param IsInstanceOf $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_object($value)) {
            $result->addError($rule->getMessages()[IsInstanceOf::ERR_NOT_OBJECT]);

            return;
        }

        if (is_a($value, $rule->getFqcn(), true)) {
            return;
        }

        $message = $rule->getMessages()[IsInstanceOf::ERR_NOT_INSTANCE];

        $result->addError(preg_replace('/{{\s+expected\s+}}/', $rule->getFqcn(), $message));
    }

    public function canCheck(): array
    {
        return [
            IsInstanceOf::class,
        ];
    }
}
