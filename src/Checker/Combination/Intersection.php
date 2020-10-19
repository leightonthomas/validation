<?php

declare(strict_types=1);

namespace Validation\Checker\Combination;

use Validation\Checker\Checker;
use Validation\Rule\Combination\Intersection as IntersectionRule;
use Validation\Rule\Rule;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

/**
 * @implements Checker<IntersectionRule<mixed, object>>
 */
class Intersection implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param IntersectionRule $rule
     * @psalm-param IntersectionRule<mixed, object> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        $customMessage = $rule->getMessages()[IntersectionRule::ERR_INVALID] ?? null;

        foreach ($rule->getRules() as $subRule) {
            $ruleResult = $this->factory->create($subRule)->validate($value);
            if ($ruleResult->isValid()) {
                continue;
            }

            if ($customMessage !== null) {
                $result->addError($customMessage);

                return;
            }

            $result->mergeAtCurrentPath($ruleResult);
        }
    }

    public function canCheck(): array
    {
        return [
            IntersectionRule::class,
        ];
    }
}
