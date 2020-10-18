<?php

declare(strict_types=1);

namespace Validation\Checker\Arrays;

use Validation\Rule\Anything;
use Validation\Rule\Arrays\IsArray as IsArrayRule;
use Validation\Rule\Combination\Union;
use Validation\Rule\Rule;
use Validation\Checker\Checker;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

use function is_array;
use function preg_replace;

/**
 * @implements Checker<IsArrayRule<array-key, mixed>>
 */
class IsArray implements Checker
{

    private ValidatorFactory $factory;

    public function __construct(ValidatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     *
     * @param IsArrayRule $rule
     * @psalm-param IsArrayRule<array-key, mixed> $rule
     */
    public function check(
        $value,
        Rule $rule,
        ValidationResult $result
    ): void
    {
        if ( ! is_array($value)) {
            $result->addError($rule->getMessages()[IsArrayRule::ERR_NOT_ARRAY]);

            return;
        }

        $keyValidator = $this->factory->create($rule->getKeyRule() ?? Union::of(new IsString())->or(new IsInteger()));
        $valueValidator = $this->factory->create($rule->getValueRule() ?? new Anything());

        $anyBadValueMessage = $rule->getMessages()[IsArrayRule::ERR_ANY_INVALID_VALUE] ?? null;
        $badKeyMessage = $rule->getMessages()[IsArrayRule::ERR_BAD_KEY];

        /**
         * @psalm-var array-key $key
         *
         * @var mixed $key
         * @var mixed $datum
         */
        foreach ($value as $key => $datum) {
            $result->addToPath((string) $key);

            $keyResult = $keyValidator->validate($key);
            if ( ! $keyResult->isValid()) {
                $result->addError(preg_replace('/{{\s+key\s+}}/', (string) $key, $badKeyMessage));
                $result->removeLastPath();

                continue;
            }

            $valueResult = $valueValidator->validate($datum);
            if ( ! $valueResult->isValid()) {
                if ($anyBadValueMessage !== null) {
                    $result->addError($anyBadValueMessage);
                    $result->removeLastPath();

                    break;
                }

                $result->mergeAtCurrentPath($valueResult);
            }

            $result->removeLastPath();
        }
    }

    public function canCheck(): array
    {
        return [
            IsArrayRule::class,
        ];
    }
}
