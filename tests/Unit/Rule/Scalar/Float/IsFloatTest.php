<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Scalar\Float;

use Codeception\PHPUnit\TestCase;
use Validation\Rule\Scalar\Float\IsFloat;
use Validation\Rule\Scalar\IsScalar;

class IsFloatTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreTypeAnDefaultMessagesOnConstruction(): void
    {
        $instance = new IsFloat();

        self::assertSame(IsScalar::SCALAR_FLOAT, $instance->getType());
        self::assertSame(
            [
                IsScalar::ERR_MESSAGE => "This value must be of type {{ expectedType }}.",
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillAllowMessagesToBeOverridden(): void
    {
        $instance = new IsFloat();
        $instance->setMessage(IsScalar::ERR_MESSAGE, 'my new msg');

        self::assertSame(
            [
                IsScalar::ERR_MESSAGE => "my new msg",
            ],
            $instance->getMessages(),
        );
    }
}
