<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Combination;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Combination\Intersection;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

/**
 * @implements Checker<Intersection<mixed, object>>
 */
class IntersectionChecker implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param Intersection $rule
     * @psalm-param Intersection<mixed, object> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        $customMessage = $rule->getMessages()[Intersection::ERR_INVALID] ?? null;

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
            Intersection::class,
        ];
    }
}
