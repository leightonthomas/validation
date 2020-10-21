<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Scalar\Integer;

use Codeception\PHPUnit\TestCase;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\IsScalar;

class IsIntegerTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreTypeAnDefaultMessagesOnConstruction(): void
    {
        $instance = new IsInteger();

        self::assertSame(IsScalar::SCALAR_INT, $instance->getType());
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
        $instance = new IsInteger();
        $instance->setMessage(IsScalar::ERR_MESSAGE, 'my new msg');

        self::assertSame(
            [
                IsScalar::ERR_MESSAGE => "my new msg",
            ],
            $instance->getMessages(),
        );
    }
}
