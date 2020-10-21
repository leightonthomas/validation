<?php

declare(strict_types=1);

namespace Validation\Checker\Arrays;

use Validation\Checker\Checker;
use Validation\Rule\Arrays\IsDefinedArray;
use Validation\Rule\Rule;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

use function array_key_exists;
use function is_array;

/**
 * @implements Checker<IsDefinedArray<array>>
 */
class IsDefinedArrayChecker implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param IsDefinedArray $rule
     * @psalm-param IsDefinedArray<array> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void {
        if (! is_array($value)) {
            $result->addError($rule->getMessages()[IsDefinedArray::ERR_NOT_ARRAY]);

            return;
        }

        foreach ($rule->getPairs() as $expectedKey => $arrayPair) {
            $result->addToPath((string) $expectedKey);

            $keyExists = array_key_exists($expectedKey, $value);
            if (! $keyExists) {
                if ($arrayPair->required) {
                    $result->addError($rule->getMessages()[IsDefinedArray::ERR_KEY_MISSING]);
                }

                $result->removeLastPath();

                continue;
            }

            $valueResult = $this->factory->create($arrayPair->rule)->validate($value[$expectedKey]);
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
            IsDefinedArray::class,
        ];
    }
}
