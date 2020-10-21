<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Combination;

use LeightonThomas\Validation\Checker\Arrays\IsArrayChecker;
use LeightonThomas\Validation\Checker\Combination\UnionChecker;
use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

class UnionCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalarChecker());
        $this->factory->register(new IsArrayChecker($this->factory));
        $this->factory->register(new UnionChecker($this->factory));
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNoRulesMatchedWhenOnlyOneRule(): void
    {
        $rule = Union::of(new IsString());

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value is invalid.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNoRulesMatchedWhenMultipleRules(): void
    {
        $rule = Union::of(new IsString())
            ->or(new IsInteger())
            ->or(new IsBoolean())
        ;

        $result = $this->factory->create($rule)->validate([]);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value is invalid.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfARuleMatchesWhenOnlyOneRule(): void
    {
        $rule = Union::of(new IsString());

        $result = $this->factory->create($rule)->validate('hi');

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    /**
     * @test
     * @dataProvider validDataProvider
     *
     * @param string|int|bool $validDatum
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfARuleMatchesWhenMultipleRules($validDatum): void
    {
        $rule = Union::of(new IsString())
            ->or(new IsInteger())
            ->or(new IsBoolean())
        ;

        $result = $this->factory->create($rule)->validate($validDatum);

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    public function validDataProvider(): iterable
    {
        return [
            ['hi'],
            [4],
            [true],
            [false],
        ];
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomErrorMessageIfSet(): void
    {
        $rule = Union::of(new IsString());
        $rule->setMessage(Union::ERR_MESSAGE, 'my custom msg');

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my custom msg'],
            $result->getErrors(),
        );
    }
}
