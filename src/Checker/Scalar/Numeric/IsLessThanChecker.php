<?php

declare(strict_types=1);

namespace Validation\Checker\Scalar\Numeric;

use Validation\Checker\Checker;
use Validation\Rule\Rule;
use Validation\Rule\Scalar\Numeric\IsLessThan;
use Validation\ValidationResult;

use function is_numeric;
use function preg_replace;

/**
 * @implements Checker<IsLessThan>
 */
class IsLessThanChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param IsLessThan $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_numeric($value)) {
            $result->addError($rule->getMessages()[IsLessThan::ERR_NOT_NUMERIC]);

            return;
        }

        $allowEqual = $rule->shouldAllowEqual();

        if ($value < $rule->getValue()) {
            return;
        }

        if ($allowEqual && ($value === $rule->getValue())) {
            return;
        }

        if ($value > $rule->getValue()) {
            $selectedMessage = $rule->getMessages()[
                $allowEqual ? IsLessThan::ERR_GREATER_THAN_OR_EQUAL : IsLessThan::ERR_GREATER_THAN
            ];

            $result->addError($this->processTemplatedMessage($rule, $selectedMessage));

            return;
        }

        $result->addError($this->processTemplatedMessage($rule, $rule->getMessages()[IsLessThan::ERR_IS_EQUAL]));
    }

    public function canCheck(): array
    {
        return [
            IsLessThan::class,
        ];
    }

    private function processTemplatedMessage(IsLessThan $rule, string $message): string
    {
        return preg_replace(
            '/{{\s*value\s*}}/',
            (string) $rule->getValue(),
            $message,
        );
    }
}
