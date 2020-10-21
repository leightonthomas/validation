<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule;

use LeightonThomas\Validation\Rule\StrictEquals;
use PHPUnit\Framework\TestCase;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;

class StrictEqualsTest extends TestCase
{

    /**
     * @test
     * @dataProvider allTypesProvider
     *
     * @param mixed $value
     */
    public function itWillHaveNoMessages($value): void
    {
        $instance = new StrictEquals($value);

        self::assertSame($value, $instance->getValue());
        self::assertSame(
            [StrictEquals::ERR_INVALID => 'This value is invalid.'],
            $instance->getMessages(),
        );
    }

    public function allTypesProvider(): iterable
    {
        yield from TypeProvider::getTypes();
    }

    /**
     * @test
     */
    public function itWillAllowAMessageToBeOverwritten(): void
    {
        $instance = new StrictEquals(4);
        $instance->setMessage(StrictEquals::ERR_INVALID, 'my new msg');

        self::assertSame(
            [
                StrictEquals::ERR_INVALID => 'my new msg',
            ],
            $instance->getMessages(),
        );
    }
}
