<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Scalar\Strings;

use Codeception\PHPUnit\TestCase;
use InvalidArgumentException;
use LeightonThomas\Validation\Rule\Scalar\Strings\Length;

class LengthTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreMinMaxInclusivityAndDefaultMessagesOnConstruction(): void
    {
        $instance = new Length(1, 2);

        self::assertSame(1, $instance->getMin());
        self::assertSame(2, $instance->getMax());
        self::assertSame(
            [
                Length::ERR_TOO_SHORT => 'This value must be at least {{ minimum }} character(s) long.',
                Length::ERR_TOO_LONG => 'This value must be at most {{ maximum }} character(s) long.',
                Length::ERR_NOT_STRING => 'This value must be of type string.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillStoreDefaultsOnConstructionIfNotProvided(): void
    {
        $instance1 = new Length(1);

        self::assertSame(1, $instance1->getMin());
        self::assertNull($instance1->getMax());

        $instance2 = new Length(null, 1);

        self::assertNull($instance2->getMin());
        self::assertSame(1, $instance2->getMax());
    }

    /**
     * @test
     */
    public function itWillThrowOnConstructionIfMinAndMaxAreBothNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You must provide either a minimum or a maximum.');

        new Length();
    }

    /**
     * @test
     */
    public function itWillAllowMessagesToBeOverridden(): void
    {
        $instance = new Length(1);
        $instance->setMessage(Length::ERR_TOO_SHORT, 'my new msg 1');
        $instance->setMessage(Length::ERR_TOO_LONG, 'my new msg 3');
        $instance->setMessage(Length::ERR_NOT_STRING, 'my new msg 5');

        self::assertSame(
            [
                Length::ERR_TOO_SHORT => 'my new msg 1',
                Length::ERR_TOO_LONG => 'my new msg 3',
                Length::ERR_NOT_STRING => 'my new msg 5',
            ],
            $instance->getMessages(),
        );
    }
}
