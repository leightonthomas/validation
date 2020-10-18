<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Combination;

use PHPUnit\Framework\TestCase;
use Validation\Rule\Combination\Union;
use Validation\Rule\Scalar\Boolean\IsBoolean;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class UnionTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreCustomRuleAndDefaultsOnConstruction(): void
    {
        $ruleA = new IsString();

        $instance = Union::of($ruleA);

        self::assertSame([$ruleA], $instance->getRules());
        self::assertSame(
            [
                Union::ERR_MESSAGE => "This value is invalid.",
            ],
            $instance->getMessages(),
        );
    }

    /**
     * @test
     */
    public function itWillStoreAdditionalRules(): void
    {
        $ruleA = new IsString();
        $ruleB = new IsInteger();
        $ruleC = new IsBoolean();

        $instance = Union::of($ruleA)
            ->or($ruleB)
            ->or($ruleA)
            ->or($ruleC)
        ;

        self::assertSame([$ruleA, $ruleB, $ruleA, $ruleC], $instance->getRules());
    }

    /**
     * @test
     */
    public function itWillAllowAMessageToBeOverwritten(): void
    {
        $instance = Union::of(new IsString());
        $instance->setMessage(Union::ERR_MESSAGE, 'my new msg');

        self::assertSame(
            [
                Union::ERR_MESSAGE => 'my new msg',
            ],
            $instance->getMessages(),
        );
    }
}
