<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule;

use PHPUnit\Framework\TestCase;
use Validation\Rule\Anything;

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
