<?php

declare(strict_types=1);

namespace Validation\Checker\Scalar\Numeric;

use Validation\Checker\Checker;
use Validation\Rule\Rule;
use Validation\Rule\Scalar\Numeric\IsGreaterThan;
use Validation\ValidationResult;

use function is_numeric;
use function preg_replace;

/**
 * @implements Checker<IsGreaterThan>
 */
class IsGreaterThanChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param IsGreaterThan $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_numeric($value)) {
            $result->addError($rule->getMessages()[IsGreaterThan::ERR_NOT_NUMERIC]);

            return;
        }

        $allowEqual = $rule->shouldAllowEqual();

        if ($value > $rule->getValue()) {
            return;
        }

        if ($allowEqual && ($value === $rule->getValue())) {
            return;
        }

        if ($value < $rule->getValue()) {
            $selectedMessage = $rule->getMessages()[
                $allowEqual ? IsGreaterThan::ERR_LESS_THAN_OR_EQUAL : IsGreaterThan::ERR_LESS_THAN
            ];

            $result->addError($this->processTemplatedMessage($rule, $selectedMessage));

            return;
        }

        $result->addError($this->processTemplatedMessage($rule, $rule->getMessages()[IsGreaterThan::ERR_IS_EQUAL]));
    }

    public function canCheck(): array
    {
        return [
            IsGreaterThan::class,
        ];
    }

    private function processTemplatedMessage(IsGreaterThan $rule, string $message): string
    {
        return preg_replace(
            '/{{\s*value\s*}}/',
            (string) $rule->getValue(),
            $message,
        );
    }
}
