<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Object;

use PHPUnit\Framework\TestCase;
use stdClass;
use Validation\Rule\Object\IsInstanceOf;

class IsInstanceOfTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreDefaultsOnConstruction(): void
    {
        $instance = new IsInstanceOf(stdClass::class);

        self::assertEquals('stdClass', $instance->getFqcn());
        self::assertSame(
            [
                IsInstanceOf::ERR_NOT_OBJECT => 'This value must be an object.',
                IsInstanceOf::ERR_NOT_INSTANCE => 'This value must be an instance of {{ expected }}.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillAllowAMessageToBeOverwritten(): void
    {
        $instance = new IsInstanceOf(stdClass::class);
        $instance->setMessage(IsInstanceOf::ERR_NOT_OBJECT, 'my new msg');

        self::assertSame(
            [
                IsInstanceOf::ERR_NOT_OBJECT => 'my new msg',
                IsInstanceOf::ERR_NOT_INSTANCE => 'This value must be an instance of {{ expected }}.',
            ],
            $instance->getMessages(),
        );
    }
}
