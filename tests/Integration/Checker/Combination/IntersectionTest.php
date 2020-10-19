<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Combination;

use stdClass;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Arrays\IsArray;
use Validation\Checker\Checker;
use Validation\Checker\Combination\Intersection;
use Validation\Checker\Object\IsInstanceOf;
use Validation\Checker\Scalar\IsScalar;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Combination\Intersection as IntersectionRule;
use Validation\Rule\Object\IsInstanceOf as IsInstanceOfRule;
use Validation\Rule\Rule;
use Validation\ValidatorFactory;

class IntersectionTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalar());
        $this->factory->register(new IsArray($this->factory));
        $this->factory->register(new Intersection($this->factory));
        $this->factory->register(new IsInstanceOf());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfARuleDoesNotMatchWhenOnlyOneRule(): void
    {
        $rule = IntersectionRule::of(new IsInstanceOfRule(ValidatorFactory::class));

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be an instance of Validation\ValidatorFactory.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorForAllRulesThatDoNotMatchWhenMultipleRules(): void
    {
        $rule = IntersectionRule::of(new IsInstanceOfRule(stdClass::class))
            ->and(new IsInstanceOfRule(Rule::class))
            ->and(new IsInstanceOfRule(Checker::class))
        ;

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            [
                'This value must be an instance of Validation\Rule\Rule.',
                'This value must be an instance of Validation\Checker\Checker.',
            ],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfAllRulesMatchWhenOnlyOneRule(): void
    {
        $rule = IntersectionRule::of(new IsInstanceOfRule(stdClass::class));

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfARuleMatchesWhenMultipleRules(): void
    {
        $rule = IntersectionRule::of(new IsInstanceOfRule(stdClass::class))
            ->and(new IsInstanceOfRule(stdClass::class))
            ->and(new IsInstanceOfRule(stdClass::class))
        ;

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomErrorMessageIfSet(): void
    {
        $rule = IntersectionRule::of(new IsInstanceOfRule(stdClass::class))
            ->and(new IsInstanceOfRule(Rule::class))
            ->and(new IsInstanceOfRule(Checker::class))
        ;
        $rule->setMessage(IntersectionRule::ERR_INVALID, 'my custom msg');

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my custom msg'],
            $result->getErrors(),
        );
    }
}
