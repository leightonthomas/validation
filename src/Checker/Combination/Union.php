<?php

declare(strict_types=1);

namespace Validation\Checker\Combination;

use Validation\Rule\Combination\Union as UnionRule;
use Validation\Rule\Rule;
use Validation\Checker\Checker;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

/**
 * @implements Checker<UnionRule>
 */
class Union implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param UnionRule $rule
     * @psalm-param UnionRule<mixed, mixed> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void
    {
        foreach ($rule->getRules() as $subRule) {
            if ($this->factory->create($subRule)->validate($value)->isValid()) {
                return;
            }
        }

        $result->addError($rule->getMessages()[UnionRule::ERR_MESSAGE]);
    }

    public function canCheck(): array
    {
        return [
            UnionRule::class,
        ];
    }
}
