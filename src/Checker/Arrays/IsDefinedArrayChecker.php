<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Checker\Arrays;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

use function array_diff_key;
use function array_key_exists;
use function array_keys;
use function array_map;
use function count;
use function is_array;
use function var_dump;

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
     * @param mixed $value
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

        if (! $rule->shouldAllowOtherKeys()) {
            $difference = array_keys(array_diff_key($value, $rule->getPairs()));

            array_map(
                function ($key) use ($result, $rule): void {
                    $result->addToPath((string) $key);

                    $result->addError($rule->getMessages()[IsDefinedArray::ERR_UNKNOWN_KEY]);

                    $result->removeLastPath();
                },
                $difference,
            );
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
