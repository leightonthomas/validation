<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Arrays;

use Validation\Rule\Arrays\IsArray;
use PHPUnit\Framework\TestCase;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class IsArrayTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreDefaultsOnConstruction(): void
    {
        $instance = new IsArray();

        self::assertNull($instance->getKeyRule());
        self::assertNull($instance->getValueRule());
        self::assertSame(
            [
                IsArray::ERR_NOT_ARRAY => 'This value must be an array.',
                IsArray::ERR_BAD_KEY => 'This key is invalid.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillStoreCustomRulesOnConstruction(): void
    {
        $keyRule = new IsString();
        $valueRule = new IsInteger();

        $instance = new IsArray($keyRule, $valueRule);

        self::assertSame($keyRule, $instance->getKeyRule());
        self::assertSame($valueRule, $instance->getValueRule());
    }

    /**
     * @test
     */
    public function itWillAllowAMessageToBeOverwritten(): void
    {
        $instance = new IsArray();
        $instance->setMessage(IsArray::ERR_NOT_ARRAY, 'my new msg');

        self::assertSame(
            [
                IsArray::ERR_NOT_ARRAY => 'my new msg',
                IsArray::ERR_BAD_KEY => 'This key is invalid.',
            ],
            $instance->getMessages(),
        );
    }
}
