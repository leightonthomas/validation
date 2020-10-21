<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Scalar\Numeric;

use LeightonThomas\Validation\Checker\Scalar\Numeric\IsGreaterThanChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

use function is_numeric;

class IsGreaterThanCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsGreaterThanChecker());
    }

    /**
     * @test
     * @dataProvider notNumericProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotNumeric($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be numeric.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notNumericProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomErrorIfNotAStringAndConfigured($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);
        $rule->setMessage(IsGreaterThan::ERR_NOT_NUMERIC, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function notNumericProvider(): iterable
    {
        $compareTo = [5, 5.0, '5', '5.0'];

        foreach (TypeProvider::getTypes() as $typeName => [$type]) {
            if (is_numeric($type)) {
                continue;
            }

            foreach ($compareTo as $comparisonValue) {
                yield [$type, $comparisonValue];
            }
        }
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTooSmall($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be greater than {$comparisonValue}."],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomerErrorIfConfiguredAndTooSmall($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);
        $rule->setMessage(IsGreaterThan::ERR_LESS_THAN, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider equalToProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfEqualToValueAndNotAllowedToBeEqual($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be greater than {$comparisonValue}."],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider equalToProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomErrorIfConfiguredIfEqualToValueAndNotAllowedToBeEqual($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);
        $rule->setMessage(IsGreaterThan::ERR_IS_EQUAL, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function equalToProvider(): iterable
    {
        $comparisonValues = [5, 5.0, '5.0', '5'];

        foreach ($comparisonValues as $value1) {
            foreach ($comparisonValues as $value2) {
                yield [$value1, $value2];
            }
        }
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTooSmallAndEqualIsAllowed($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);
        $rule->allowEqual();

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be greater than or equal to {$comparisonValue}."],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int|float|string $value
     * @param int|float|string $comparisonValue
     *
     * @psalm-param numeric $value
     * @psalm-param numeric $comparisonValue
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomerErrorIfConfiguredAndTooSmallAndEqualIsAllowed($value, $comparisonValue): void
    {
        $rule = new IsGreaterThan($comparisonValue);
        $rule->allowEqual();
        $rule->setMessage(IsGreaterThan::ERR_LESS_THAN_OR_EQUAL, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function tooSmallProvider(): iterable
    {
        $transformations = [
            'int' => fn(int $i): int => $i,
            'numeric-string' => fn(int $i): string => (string) $i,
            'numeric-string (decimal)' => fn(int $i): string => ((string) $i) . '.00',
            'float' => fn(int $i): float => (float) $i,
        ];

        $comparisonValues = [100, 100.0, '100', '100.0'];

        foreach ([-100, -50, 0, -0, 10, 50, 99] as $value) {
            foreach ($comparisonValues as $comparisonValue) {
                foreach ($transformations as $resultType => $transformation) {
                    yield [$transformation($value), $comparisonValue];
                }
            }
        }
    }
}
