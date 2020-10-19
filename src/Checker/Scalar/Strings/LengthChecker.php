<?php

declare(strict_types=1);

namespace Validation\Checker\Scalar\Strings;

use Validation\Checker\Checker;
use Validation\Rule\Rule;
use Validation\Rule\Scalar\Strings\Length;
use Validation\ValidationResult;

use function is_string;
use function preg_replace;
use function strlen;

/**
 * @implements Checker<Length>
 */
class LengthChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param Length $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_string($value)) {
            $result->addError($rule->getMessages()[Length::ERR_NOT_STRING]);

            return;
        }

        $min = $rule->getMin();
        $max = $rule->getMax();

        $length = strlen($value);

        $minMessage = $this->processTemplatedMessage(
            $rule,
            $rule->getMessages()[Length::ERR_TOO_SHORT],
        );
        $maxMessage = $this->processTemplatedMessage(
            $rule,
            $rule->getMessages()[Length::ERR_TOO_LONG],
        );

        if (($min !== null) && ($length < $min)) {
            $result->addError($minMessage);

            return;
        }

        if (($max !== null) && ($length > $max)) {
            $result->addError($maxMessage);

            return;
        }
    }

    public function canCheck(): array
    {
        return [
            Length::class,
        ];
    }

    private function processTemplatedMessage(Length $rule, string $message): string
    {
        $message = preg_replace(
            '/{{\s*minimum\s*}}/',
            (string) $rule->getMin(),
            $message,
        );

        $message = preg_replace(
            '/{{\s*maximum\s*}}/',
            (string) $rule->getMax(),
            $message,
        );

        return $message;
    }
}
