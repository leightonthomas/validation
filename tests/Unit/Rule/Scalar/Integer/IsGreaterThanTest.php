<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Scalar\Integer;

use Codeception\PHPUnit\TestCase;
use Validation\Rule\Scalar\Integer\IsGreaterThan;

class IsGreaterThanTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreTypeAnDefaultMessagesOnConstruction(): void
    {
        $instance = new IsGreaterThan(4);

        self::assertSame(4, $instance->getValue());
        self::assertFalse($instance->shouldAllowEqual());
        self::assertSame(
            [
                IsGreaterThan::ERR_LESS_THAN => 'This value must be greater than {{ value }}.',
                IsGreaterThan::ERR_IS_EQUAL => 'This value must be greater than {{ value }}.',
                IsGreaterThan::ERR_LESS_THAN_OR_EQUAL => 'This value must be greater than or equal to {{ value }}.',
                IsGreaterThan::ERR_NOT_INTEGER => 'This value must be an integer.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillAllowMessagesToBeOverridden(): void
    {
        $instance = new IsGreaterThan(4);
        $instance->setMessage(IsGreaterThan::ERR_IS_EQUAL, 'my new msg');

        self::assertSame(
            [
                IsGreaterThan::ERR_LESS_THAN => 'This value must be greater than {{ value }}.',
                IsGreaterThan::ERR_IS_EQUAL => 'my new msg',
                IsGreaterThan::ERR_LESS_THAN_OR_EQUAL => 'This value must be greater than or equal to {{ value }}.',
                IsGreaterThan::ERR_NOT_INTEGER => 'This value must be an integer.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function allowEqualWillChangeWhetherOrNotItShouldAllowValuesToBeEqual(): void
    {
        $instance = new IsGreaterThan(4);
        $instance->allowEqual();

        self::assertTrue($instance->shouldAllowEqual());
    }
}
