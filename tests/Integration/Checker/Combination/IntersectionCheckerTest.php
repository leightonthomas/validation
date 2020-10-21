<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Combination;

use LeightonThomas\Validation\Checker\Arrays\IsArrayChecker;
use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Checker\Combination\IntersectionChecker;
use LeightonThomas\Validation\Checker\Object\IsInstanceOfChecker;
use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Combination\Intersection;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidatorFactory;
use stdClass;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

class IntersectionCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalarChecker());
        $this->factory->register(new IsArrayChecker($this->factory));
        $this->factory->register(new IntersectionChecker($this->factory));
        $this->factory->register(new IsInstanceOfChecker());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfARuleDoesNotMatchWhenOnlyOneRule(): void
    {
        $rule = Intersection::of(new IsInstanceOf(ValidatorFactory::class));

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be an instance of LeightonThomas\Validation\ValidatorFactory.'],
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
        $rule = Intersection::of(new IsInstanceOf(stdClass::class))
            ->and(new IsInstanceOf(Rule::class))
            ->and(new IsInstanceOf(Checker::class))
        ;

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            [
                'This value must be an instance of LeightonThomas\Validation\Rule\Rule.',
                'This value must be an instance of LeightonThomas\Validation\Checker\Checker.',
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
        $rule = Intersection::of(new IsInstanceOf(stdClass::class));

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
        $rule = Intersection::of(new IsInstanceOf(stdClass::class))
            ->and(new IsInstanceOf(stdClass::class))
            ->and(new IsInstanceOf(stdClass::class))
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
        $rule = Intersection::of(new IsInstanceOf(stdClass::class))
            ->and(new IsInstanceOf(Rule::class))
            ->and(new IsInstanceOf(Checker::class))
        ;
        $rule->setMessage(Intersection::ERR_INVALID, 'my custom msg');

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my custom msg'],
            $result->getErrors(),
        );
    }
}
