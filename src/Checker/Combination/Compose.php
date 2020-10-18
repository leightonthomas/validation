<?php

declare(strict_types=1);

namespace Validation\Checker\Combination;

use Validation\Rule\Combination\Compose as ComposeRule;
use Validation\Rule\Rule;
use Validation\Checker\Checker;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

/**
 * @implements Checker<ComposeRule>
 */
class Compose implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param ComposeRule $rule
     * @psalm-param ComposeRule<mixed, mixed> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void
    {
        foreach ($rule->getRules() as $subRule) {
            $subResult = $this->factory->create($subRule)->validate($value);

            if ($subResult->isValid()) {
                continue;
            }

            $customMessage = $rule->getMessages()[ComposeRule::ERR_MESSAGE] ?? null;
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
            ComposeRule::class,
        ];
    }
}
