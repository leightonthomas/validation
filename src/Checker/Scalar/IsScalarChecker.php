<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Scalar;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\Float\IsFloat;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\IsScalar;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use LeightonThomas\Validation\ValidationResult;

use function gettype;
use function preg_replace;

/**
 * @implements Checker<IsScalar>
 */
class IsScalarChecker implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param IsScalar $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        $expected = $rule->getType();
        $type = gettype($value);
        if ($type === $expected) {
            return;
        }

        $message = $rule->getMessages()[IsScalar::ERR_MESSAGE];

        $result->addError(preg_replace('/{{\s+expectedType\s+}}/', $expected, $message));
    }

    public function canCheck(): array
    {
        return [
            IsString::class,
            IsInteger::class,
            IsFloat::class,
            IsBoolean::class,
        ];
    }
}
