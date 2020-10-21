<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Rule\Combination;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Combination\Intersection;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\TestCase;

class IntersectionTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreCustomRuleAndDefaultsOnConstruction(): void
    {
        $ruleA = new IsInstanceOf(ValidatorFactory::class);

        $instance = Intersection::of($ruleA);

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
        $ruleA = new IsInstanceOf(ValidatorFactory::class);
        $ruleB = new IsInstanceOf(Checker::class);
        $ruleC = new IsInstanceOf(Rule::class);

        $instance = Intersection::of($ruleA)
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
        $instance = Intersection::of(new IsInstanceOf(ValidatorFactory::class));
        $instance->setMessage(Intersection::ERR_INVALID, 'my new msg');

        self::assertSame(
            [
                Intersection::ERR_INVALID => 'my new msg',
            ],
            $instance->getMessages(),
        );
    }
}
