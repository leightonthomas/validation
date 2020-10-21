<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Combination;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

/**
 * @implements Checker<Union>
 */
class UnionChecker implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param Union $rule
     * @psalm-param Union<mixed, mixed> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        foreach ($rule->getRules() as $subRule) {
            if ($this->factory->create($subRule)->validate($value)->isValid()) {
                return;
            }
        }

        $result->addError($rule->getMessages()[Union::ERR_MESSAGE]);
    }

    public function canCheck(): array
    {
        return [
            Union::class,
        ];
    }
}
