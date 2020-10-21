<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Scalar\Integer;

use Tests\Validation\DataProvider\TypeProvider;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Scalar\Integer\IsGreaterThanChecker;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Scalar\Integer\IsGreaterThan;

use function is_int;

class IsGreaterThanCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsGreaterThanChecker());
    }

    /**
     * @test
     * @dataProvider notIntegerProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotAnInteger($value): void
    {
        $rule = new IsGreaterThan(100);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be an integer.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notIntegerProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomErrorIfNotAStringAndConfigured($value): void
    {
        $rule = new IsGreaterThan(100);
        $rule->setMessage(IsGreaterThan::ERR_NOT_INTEGER, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function notIntegerProvider(): iterable
    {
        foreach (TypeProvider::getTypes() as $typeName => [$type]) {
            if (is_int($type)) {
                continue;
            }

            yield $typeName => [$type];
        }
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTooSmall(int $value): void
    {
        $rule = new IsGreaterThan(100);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be greater than 100.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomerErrorIfConfiguredAndTooShort(int $value): void
    {
        $rule = new IsGreaterThan(100);
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
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfEqualToValueAndNotAllowedToBeEqual(): void
    {
        $rule = new IsGreaterThan(100);

        $result = $this->factory->create($rule)->validate(100);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be greater than 100.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomErrorIfConfiguredIfEqualToValueAndNotAllowedToBeEqual(): void
    {
        $rule = new IsGreaterThan(100);
        $rule->setMessage(IsGreaterThan::ERR_IS_EQUAL, 'my msg');

        $result = $this->factory->create($rule)->validate(100);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTooSmallAndEqualIsAllowed(int $value): void
    {
        $rule = new IsGreaterThan(100);
        $rule->allowEqual();

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be greater than or equal to 100.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooSmallProvider
     *
     * @param int $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomerErrorIfConfiguredAndTooShortAndEqualIsAllowed(int $value): void
    {
        $rule = new IsGreaterThan(100);
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
        foreach ([-100, -50, -0, 0, 25, 50, 90, 99] as $value) {
            yield [$value];
        }
    }
}
