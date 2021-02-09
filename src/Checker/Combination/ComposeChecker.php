<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Combination;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Combination\Compose;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

/**
 * @implements Checker<Compose>
 */
class ComposeChecker implements Checker
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
     * @param Compose $rule
     * @psalm-param Compose<mixed, mixed> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        foreach ($rule->getRules() as $subRule) {
            $subResult = $this->factory->create($subRule)->validate($value);

            if ($subResult->isValid()) {
                continue;
            }

            $customMessage = $rule->getMessages()[Compose::ERR_MESSAGE] ?? null;
            if ($customMessage !== null) {
                $result->addError($customMessage);
            } else {
                $result->mergeAtCurrentPath($subResult);
            }

            break;
        }
    }

    public function canCheck(): array
    {
        return [
            Compose::class,
        ];
    }
}
