<?php

declare(strict_types=1);

namespace Validation\Checker\Arrays;

use Validation\Rule\Arrays\IsDefinedArray as IsDefinedArrayRule;
use Validation\Rule\Rule;
use Validation\Checker\Checker;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

use function array_key_exists;
use function is_array;

/**
 * @implements Checker<IsDefinedArrayRule<array>>
 */
class IsDefinedArray implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param IsDefinedArrayRule $rule
     * @psalm-param IsDefinedArrayRule<array> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void
    {
        if ( ! is_array($value)) {
            $result->addError($rule->getMessages()[IsDefinedArrayRule::ERR_NOT_ARRAY]);

            return;
        }

        foreach ($rule->getPairs() as $expectedKey => $valueRule) {
            $result->addToPath((string) $expectedKey);

            if ( ! array_key_exists($expectedKey, $value)) {
                $result->addError($rule->getMessages()[IsDefinedArrayRule::ERR_KEY_MISSING]);

                $result->removeLastPath();

                continue;
            }

            $valueResult = $this->factory->create($valueRule)->validate($value[$expectedKey]);
            if ($valueResult->isValid()) {
                $result->removeLastPath();

                continue;
            }

            $result->mergeAtCurrentPath($valueResult);
            $result->removeLastPath();
        }
    }

    public function canCheck(): array
    {
        return [
            IsDefinedArrayRule::class,
        ];
    }
}
