<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Scalar\Strings;

use Codeception\PHPUnit\TestCase;
use Validation\Rule\Scalar\IsScalar;
use Validation\Rule\Scalar\Strings\IsString;

class IsStringTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreTypeAnDefaultMessagesOnConstruction(): void
    {
        $instance = new IsString();

        self::assertSame(IsScalar::SCALAR_STRING, $instance->getType());
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
        $instance = new IsString();
        $instance->setMessage(IsScalar::ERR_MESSAGE, 'my new msg');

        self::assertSame(
            [
                IsScalar::ERR_MESSAGE => "my new msg",
            ],
            $instance->getMessages(),
        );
    }
}
