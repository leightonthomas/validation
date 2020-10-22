<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker;

use LeightonThomas\Validation\Rule\Callback;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

/**
 * @implements Checker<Callback>
 */
class CallbackChecker implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-param Callback $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        $rule->getCallback()($value, $result, $this->factory);
    }

    public function canCheck(): array
    {
        return [
            Callback::class,
        ];
    }
}
