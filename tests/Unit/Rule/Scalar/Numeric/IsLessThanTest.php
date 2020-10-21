<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Scalar\Numeric;

use Codeception\PHPUnit\TestCase;
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsLessThan;

class IsLessThanTest extends TestCase
{

    /**
     * @test
     * @dataProvider numericValueProvider
     *
     * @param int|float|numeric-string $value
     * @psalm-param numeric $value
     */
    public function itWillStoreTypeAnDefaultMessagesOnConstruction($value): void
    {
        $instance = new IsLessThan($value);

        self::assertSame($value, $instance->getValue());
        self::assertFalse($instance->shouldAllowEqual());
        self::assertSame(
            [
                IsLessThan::ERR_GREATER_THAN => 'This value must be less than {{ value }}.',
                IsLessThan::ERR_IS_EQUAL => 'This value must be less than {{ value }}.',
                IsLessThan::ERR_GREATER_THAN_OR_EQUAL => 'This value must be less than or equal to {{ value }}.',
                IsLessThan::ERR_NOT_NUMERIC => 'This value must be numeric.',
            ],
            $instance->getMessages(),
        );
    }

    public function numericValueProvider(): iterable
    {
        return [
            ['4'],
            ['4.1'],
            [4.1],
            [4],
        ];
    }

    /**
     * @test
     */
    public function itWillAllowMessagesToBeOverridden(): void
    {
        $instance = new IsLessThan(4);
        $instance->setMessage(IsLessThan::ERR_IS_EQUAL, 'my new msg');

        self::assertSame(
            [
                IsLessThan::ERR_GREATER_THAN => 'This value must be less than {{ value }}.',
                IsLessThan::ERR_IS_EQUAL => 'my new msg',
                IsLessThan::ERR_GREATER_THAN_OR_EQUAL => 'This value must be less than or equal to {{ value }}.',
                IsLessThan::ERR_NOT_NUMERIC => 'This value must be numeric.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function allowEqualWillChangeWhetherOrNotItShouldAllowValuesToBeEqual(): void
    {
        $instance = new IsLessThan(4);
        $instance->allowEqual();

        self::assertTrue($instance->shouldAllowEqual());
    }
}
