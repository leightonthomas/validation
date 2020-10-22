<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Scalar\Strings;

use Codeception\PHPUnit\TestCase;
use LeightonThomas\Validation\Rule\Scalar\Strings\Regex;

class RegexTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStorePatternAndDefaultsOnConstruction(): void
    {
        $instance = new Regex('/pattern/');

        self::assertSame('/pattern/', $instance->getPattern());
        self::assertTrue($instance->shouldMatch());
        self::assertSame(
            [
                Regex::ERR_NOT_STRING => 'This value must be of type string.',
                Regex::ERR_MATCHES => 'This value is invalid.',
                Regex::ERR_DOES_NOT_MATCH => 'This value is invalid.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillUpdateShouldMatchIfDoesNotMatchIsCalled(): void
    {
        $instance = new Regex('/pattern/');
        $instance->doesNotMatch();

        self::assertFalse($instance->shouldMatch());
    }

    /**
     * @test
     */
    public function itWillAllowMessagesToBeOverridden(): void
    {
        $instance = new Regex('/pattern/');
        $instance->setMessage(Regex::ERR_NOT_STRING, 'my new msg');

        self::assertSame(
            [
                Regex::ERR_NOT_STRING => 'my new msg',
                Regex::ERR_MATCHES => 'This value is invalid.',
                Regex::ERR_DOES_NOT_MATCH => 'This value is invalid.',
            ],
            $instance->getMessages(),
        );
    }
}
