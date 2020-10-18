<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Rule\Combination;

use PHPUnit\Framework\TestCase;
use Validation\Rule\Combination\Compose;
use Validation\Rule\Scalar\Boolean\IsBoolean;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class ComposeTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreCustomRuleAndDefaultsOnConstruction(): void
    {
        $ruleA = new IsString();

        $instance = Compose::from($ruleA);

        self::assertSame([$ruleA], $instance->getRules());
        self::assertSame(
            [],
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

        $instance = Compose::from($ruleA)
            ->and($ruleB)
            ->and($ruleA)
            ->and($ruleC)
        ;

        self::assertSame([$ruleA, $ruleB, $ruleA, $ruleC], $instance->getRules());
    }

    /**
     * @test
     */
    public function itWillAllowAMessageToBeOverwritten(): void
    {
        $instance = Compose::from(new IsString());
        $instance->setMessage(Compose::ERR_MESSAGE, 'my new msg');

        self::assertSame(
            [
                Compose::ERR_MESSAGE => 'my new msg',
            ],
            $instance->getMessages(),
        );
    }
}
