<?php

declare(strict_types=1);

namespace TestsLeightonThomas\Validation\Unit\Rule\Combination;

use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use PHPUnit\Framework\TestCase;

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
