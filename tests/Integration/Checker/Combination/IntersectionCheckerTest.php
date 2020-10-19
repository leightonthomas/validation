<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Combination;

use stdClass;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Arrays\IsArrayChecker;
use Validation\Checker\Checker;
use Validation\Checker\Combination\IntersectionChecker;
use Validation\Checker\Object\IsInstanceOfChecker;
use Validation\Checker\Scalar\IsScalar;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Combination\Intersection;
use Validation\Rule\Object\IsInstanceOf;
use Validation\Rule\Rule;
use Validation\ValidatorFactory;

class IntersectionCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalar());
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
        $rule = Intersection::of(new IsInstanceOf(stdClass::class))
            ->and(new IsInstanceOf(Rule::class))
            ->and(new IsInstanceOf(Checker::class))
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
