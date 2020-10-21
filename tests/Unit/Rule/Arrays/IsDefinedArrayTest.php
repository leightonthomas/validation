<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Arrays;

use LeightonThomas\Validation\Rule\Anything;
use LeightonThomas\Validation\Rule\Arrays\ArrayPair;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\Float\IsFloat;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use PHPUnit\Framework\TestCase;

class IsDefinedArrayTest extends TestCase
{

    /**
     * @param string|int $key
     * @param Rule $rule
     * @param bool $required
     * @param ArrayPair $pair
     */
    private static function assertPair($key, Rule $rule, bool $required, ArrayPair $pair): void
    {
        self::assertSame($key, $pair->key);
        self::assertSame($rule, $pair->rule);
        self::assertSame($required, $pair->required);
    }

    /**
     * @test
     */
    public function itWillStoreTheInitialPairAndDefaultMessagesUponCreation(): void
    {
        $aRule = new IsString();

        $instance = IsDefinedArray::of('a', $aRule);
        $pairs = $instance->getPairs();

        self::assertCount(1, $pairs);
        self::assertPair('a', $aRule, true, $pairs['a']);

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
    public function itWillStoreTheInitialPairAndDefaultMessagesUponCreationWithOptionalKey(): void
    {
        $aRule = new IsString();

        $instance = IsDefinedArray::ofMaybe('a', $aRule);
        $pairs = $instance->getPairs();

        self::assertCount(1, $pairs);
        self::assertPair('a', $aRule, false, $pairs['a']);

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
    public function itWillStoreAdditionalRequiredKeys(): void
    {
        $aRule = new IsString();
        $bRule = new IsInteger();

        $instance = IsDefinedArray::of('a', $aRule)
            ->and('b', $bRule)
        ;

        $pairs = $instance->getPairs();

        self::assertCount(2, $pairs);
        self::assertPair('a', $aRule, true, $pairs['a']);
        self::assertPair('b', $bRule, true, $pairs['b']);
    }

    /**
     * @test
     */
    public function itWillStoreAdditionalOptionalKeys(): void
    {
        $aRule = new IsString();
        $bRule = new IsInteger();
        $cRule = new IsBoolean();

        $instance = IsDefinedArray::of('a', $aRule)
            ->andMaybe('b', $bRule)
            ->andMaybe('c', $cRule)
        ;

        $pairs = $instance->getPairs();

        self::assertCount(3, $pairs);
        self::assertPair('a', $aRule, true, $pairs['a']);
        self::assertPair('b', $bRule, false, $pairs['b']);
        self::assertPair('c', $cRule, false, $pairs['c']);
    }

    /**
     * @test
     */
    public function itWillOverwriteKeysIfProvidedAgain(): void
    {
        $aRule = new IsString();
        $bRule = new IsInteger();
        $cRule = new IsBoolean();
        $dRule = new IsFloat();
        $eRule = new Anything();

        $instance = IsDefinedArray::of('a', $aRule)
            ->and('b', $bRule)
            ->and('b', $aRule)
            ->andMaybe('c', $cRule)
            ->andMaybe('c', $bRule)
            ->and('d', $dRule)
            ->andMaybe('d', $cRule)
            ->andMaybe('e', $eRule)
            ->and('e', $dRule)
        ;

        $pairs = $instance->getPairs();

        self::assertCount(5, $pairs);
        self::assertPair('a', $aRule, true, $pairs['a']);
        self::assertPair('b', $aRule, true, $pairs['b']);
        self::assertPair('c', $bRule, false, $pairs['c']);
        self::assertPair('d', $cRule, false, $pairs['d']);
        self::assertPair('e', $dRule, true, $pairs['e']);
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
