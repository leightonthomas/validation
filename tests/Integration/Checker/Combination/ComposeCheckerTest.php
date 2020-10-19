<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Combination;

use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Combination\ComposeChecker;
use Validation\Checker\Scalar\IsScalar;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Combination\Compose;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class ComposeCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalar());
        $this->factory->register(new ComposeChecker($this->factory));
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfDoesNotMatchWhenOnlyOneRule(): void
    {
        $rule = Compose::from(new IsString());

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be of type string.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfARuleDoesNotMatchWhenMultipleRules(): void
    {
        $rule = Compose::from(new IsString())
            ->and(new IsInteger())
        ;

        $result = $this->factory->create($rule)->validate('a');

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be of type integer.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfMatchesWhenOnlyOneRule(): void
    {
        $rule = Compose::from(new IsString());

        $result = $this->factory->create($rule)->validate('hi');

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
        $rule = Compose::from(new IsString())
            ->and(new IsString())
        ;

        $result = $this->factory->create($rule)->validate('a');

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
        $rule = Compose::from(new IsString());
        $rule->setMessage(Compose::ERR_MESSAGE, 'my custom msg');

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my custom msg'],
            $result->getErrors(),
        );
    }
}
