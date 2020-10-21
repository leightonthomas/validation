<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Scalar\Boolean;

use Codeception\PHPUnit\TestCase;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\IsScalar;

class IsBooleanTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreTypeAnDefaultMessagesOnConstruction(): void
    {
        $instance = new IsBoolean();

        self::assertSame(IsScalar::SCALAR_BOOLEAN, $instance->getType());
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
        $instance = new IsBoolean();
        $instance->setMessage(IsScalar::ERR_MESSAGE, 'my new msg');

        self::assertSame(
            [
                IsScalar::ERR_MESSAGE => "my new msg",
            ],
            $instance->getMessages(),
        );
    }
}
