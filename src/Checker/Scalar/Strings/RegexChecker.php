<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Scalar\Strings;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\Rule\Scalar\Strings\Regex;
use LeightonThomas\Validation\ValidationResult;

use function is_string;
use function preg_match;

/**
 * @implements Checker<Regex>
 */
class RegexChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param Regex $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_string($value)) {
            $result->addError($rule->getMessages()[Regex::ERR_NOT_STRING]);

            return;
        }

        $matches = preg_match($rule->getPattern(), $value) === 1;

        if ($matches) {
            if ($rule->shouldMatch()) {
                return;
            }

            $result->addError($rule->getMessages()[Regex::ERR_MATCHES]);

            return;
        }

        if (! $rule->shouldMatch()) {
            return;
        }

        $result->addError($rule->getMessages()[Regex::ERR_DOES_NOT_MATCH]);
    }

    public function canCheck(): array
    {
        return [
            Regex::class,
        ];
    }
}
