<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Arrays;

use LeightonThomas\Validation\Checker\AnythingChecker;
use LeightonThomas\Validation\Checker\Arrays\IsArrayChecker;
use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Checker\Combination\UnionChecker;
use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Arrays\IsArray;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use LeightonThomas\Validation\ValidationResult;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

use function is_array;

class IsArrayCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalarChecker());
        $this->factory->register(new IsArrayChecker($this->factory));
        $this->factory->register(new AnythingChecker());
        $this->factory->register(new UnionChecker($this->factory));
        $this->factory->register(
            new class implements Checker {

                public function check($value, Rule $rule, ValidationResult $result): void
                {
                    $result->addError('err1');
                    $result->addError('err2');
                    $result->addError('err3');
                }

                public function canCheck(): array
                {
                    return [IsDefinedArray::class];
                }
            },
        );
    }

    /**
     * @test
     * @dataProvider notArrayProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotAnArray($value): void
    {
        $rule = new IsArray();

        $result = $this->factory->create($rule)->validate($value);

        self::assertEquals(
            ['This value must be an array.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notArrayProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomMessageIfConfiguredIfNotAnArray($value): void
    {
        $rule = new IsArray();
        $rule->setMessage(IsArray::ERR_NOT_ARRAY, 'my custom message');

        $result = $this->factory->create($rule)->validate($value);

        self::assertEquals(
            ['my custom message'],
            $result->getErrors(),
        );
    }

    public function notArrayProvider(): iterable
    {
        foreach (TypeProvider::getTypes() as $name => [$value]) {
            if (is_array($value)) {
                continue;
            }

            yield $name => [$value];
        }
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillValidateWithDefaultRulesIfNoCustomOnesProvided(): void
    {
        $input = ['a' => 'b', 4 => 'c'];
        $rule = new IsArray();

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals([], $result->getErrors());
        self::assertTrue($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillValidateWithCustomRulesIfProvided(): void
    {
        $input = ['a' => 'b', 4 => 'c'];
        $keyRule = new IsString();
        $valueRule = IsDefinedArray::of('a', new IsInteger());
        $rule = new IsArray($keyRule, $valueRule);

        $result = $this->factory->create($rule)->validate($input);

        self::assertFalse($result->isValid());
        self::assertEquals(
            [
                'a' => [
                    'err1',
                    'err2',
                    'err3',
                ],
                4 => [
                    'This key is invalid.',
                ],
            ],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddErrorsIfValidDataGivenWithCustomRules(): void
    {
        $input = ['a' => 1, 'b' => 2];
        $keyRule = new IsString();
        $valueRule = new IsInteger();
        $rule = new IsArray($keyRule, $valueRule);

        $result = $this->factory->create($rule)->validate($input);

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomMessageIfAValueIsInvalidAndItIsConfiguredWithOne(): void
    {
        $input = ['a' => 'b', 4 => 'c'];
        $keyRule = new IsString();
        $valueRule = IsDefinedArray::of('a', new IsInteger());
        $rule = new IsArray($keyRule, $valueRule);
        $rule->setMessage(IsArray::ERR_ANY_INVALID_VALUE, 'my custom message');

        $result = $this->factory->create($rule)->validate($input);

        self::assertFalse($result->isValid());
        self::assertEquals(
            [
                'a' => [
                    'my custom message',
                ],
            ],
            $result->getErrors(),
        );
    }
}
