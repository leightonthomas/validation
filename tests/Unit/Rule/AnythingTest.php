<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule;

use LeightonThomas\Validation\Rule\Anything;
use PHPUnit\Framework\TestCase;

class AnythingTest extends TestCase
{

    /**
     * @test
     */
    public function itWillHaveNoMessages(): void
    {
        $instance = new Anything();

        self::assertSame([], $instance->getMessages());
    }

    /**
     * @test
     */
    public function itWillDoNothingOnAttemptToOverwriteAMessage(): void
    {
        $instance = new Anything();
        $instance->setMessage(0, 'my msg');

        self::assertSame([], $instance->getMessages());
    }
}
