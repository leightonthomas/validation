<?php

declare(strict_types=1);

namespace Validation\Checker\Combination;

use Validation\Checker\Checker;
use Validation\Rule\Combination\Compose;
use Validation\Rule\Rule;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

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
