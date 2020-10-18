<?php

declare(strict_types=1);

namespace Validation\Checker\Scalar;

use Validation\Rule\Rule;
use Validation\Rule\Scalar\Boolean\IsBoolean;
use Validation\Rule\Scalar\Float\IsFloat;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;
use Validation\Rule\Scalar\IsScalar as IsScalarRule;
use Validation\Checker\Checker;
use Validation\ValidationResult;

use function gettype;
use function preg_replace;

/**
 * @implements Checker<IsScalarRule>
 */
class IsScalar implements Checker
{

    /**
     * {@inheritdoc}
     *
     * @param IsScalarRule $rule
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

        $message = $rule->getMessages()[IsScalarRule::ERR_MESSAGE];

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
