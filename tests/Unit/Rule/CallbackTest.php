<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule;

use LeightonThomas\Validation\Rule\Callback;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{

    /**
     * @test
     * @dataProvider callableProvider
     *
     * @param callable $callable
     */
    public function itWillStoreTheCallableOnCreationAndDefaultMessages(callable $callable): void
    {
        $instance = new Callback($callable);

        self::assertSame($callable, $instance->getCallback());
        self::assertSame([], $instance->getMessages());
    }

    public function callableProvider(): array
    {
        return [
            [
                function() {
                    return 4;
                },
            ],
            [
                fn() => 4,
            ],
            [
                [$this, 'callableProvider'],
            ],
            ['str_replace'],
        ];
    }

    /**
     * @test
     */
    public function getMessagesWillAlwaysReturnEmpty(): void
    {
        $instance = new Callback(fn() => 4);
        $instance->setMessage(0, 'my msg');

        self::assertSame([], $instance->getMessages());
    }
}
