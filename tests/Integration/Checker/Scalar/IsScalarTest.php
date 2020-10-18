<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Scalar;

use Tests\Validation\DataProvider\TypeProvider;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Scalar\IsScalar;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Scalar\Boolean\IsBoolean;
use Validation\Rule\Scalar\Float\IsFloat;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\IsScalar as IsScalarRule;
use Validation\Rule\Scalar\Strings\IsString;

use function is_bool;
use function is_float;
use function is_integer;
use function is_string;

class IsScalarTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalar());
    }

    /**
     * @test
     * @dataProvider invalidProvider
     *
     * @param IsScalarRule $rule
     * @param mixed $value
     * @param string $expectedTypeName
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfTypeDoesNotMatch(IsScalarRule $rule, $value, string $expectedTypeName): void
    {
        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be of type {$expectedTypeName}."],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider validProvider
     *
     * @param IsScalarRule $rule
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfValueValid(IsScalarRule $rule, $value): void
    {
        $result = $this->factory->create($rule)->validate($value);

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    public function validProvider(): iterable
    {
        return [
            [new IsString(), 'a'],
            [new IsInteger(), 4],
            [new IsFloat(), 1.234],
            [new IsBoolean(), true],
            [new IsBoolean(), false],
        ];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     *
     * @param IsScalarRule $rule
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomErrorMessageIfSet(IsScalarRule $rule, $value): void
    {
        $rule->setMessage(IsScalarRule::ERR_MESSAGE, 'my custom msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my custom msg'],
            $result->getErrors(),
        );
    }

    public function invalidProvider(): iterable
    {
        $rules = [
            'IsString' => [new IsString(), 'string', fn($v): bool => ! is_string($v)],
            'IsInteger' => [new IsInteger(), 'integer', fn($v): bool => ! is_integer($v)],
            'IsFloat' => [new IsFloat(), 'double', fn($v): bool => ! is_float($v)],
            'IsBoolean' => [new IsBoolean(), 'boolean', fn($v): bool => ! is_bool($v)],
        ];

        foreach ($rules as $ruleName => [$rule, $expectedTypeName, $isInvalidForRule]) {
            foreach (TypeProvider::getTypes() as $typeName => $typeValue) {
                if ($isInvalidForRule($typeValue)) {
                    yield "{$ruleName} {$typeName}" => [$rule, $typeValue, $expectedTypeName];
                }
            }
        }
    }
}
