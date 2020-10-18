<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Arrays;

use Validation\Rule\Arrays\IsDefinedArray;
use PHPUnit\Framework\TestCase;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class IsDefinedArrayTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreTheInitialPairAndDefaultMessagesUponCreation(): void
    {
        $aRule = new IsString();

        $instance = IsDefinedArray::of('a', $aRule);

        self::assertSame(
            ['a' => $aRule],
            $instance->getPairs(),
        );

        self::assertSame(
            [
                IsDefinedArray::ERR_KEY_MISSING => 'This key is missing.',
                IsDefinedArray::ERR_NOT_ARRAY => 'This value must be an array.',
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillStoreAdditionalKeys(): void
    {
        $aRule = new IsString();
        $bRule = new IsInteger();

        $instance = IsDefinedArray::of('a', $aRule)
            ->and('b', $bRule)
        ;

        self::assertSame(
            ['a' => $aRule, 'b' => $bRule],
            $instance->getPairs(),
        );
    }

    /**
     * @test
     */
    public function itWillOverwriteKeysIfProvidedAgain(): void
    {
        $aRule = new IsString();
        $bRule = new IsInteger();

        $instance = IsDefinedArray::of('a', $aRule)
            ->and('b', $bRule)
            ->and('b', $aRule)
        ;

        self::assertSame(
            ['a' => $aRule, 'b' => $aRule],
            $instance->getPairs(),
        );
    }

    /**
     * @test
     */
    public function itWillAllowAMessageToBeOverwritten(): void
    {
        $instance = IsDefinedArray::of('a', new IsString());
        $instance->setMessage(IsDefinedArray::ERR_NOT_ARRAY, 'my new msg');

        self::assertSame(
            [
                IsDefinedArray::ERR_KEY_MISSING => 'This key is missing.',
                IsDefinedArray::ERR_NOT_ARRAY => 'my new msg',
            ],
            $instance->getMessages(),
        );
    }
}
