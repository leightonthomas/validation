<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Combination;

use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Arrays\IsArray;
use Validation\Checker\Combination\Union;
use Validation\Checker\Scalar\IsScalar;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Combination\Union as UnionRule;
use Validation\Rule\Scalar\Boolean\IsBoolean;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class UnionTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalar());
        $this->factory->register(new IsArray($this->factory));
        $this->factory->register(new Union($this->factory));
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNoRulesMatchedWhenOnlyOneRule(): void
    {
        $rule = UnionRule::of(new IsString());

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
        $rule = UnionRule::of(new IsString())
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
        $rule = UnionRule::of(new IsString());

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
        $rule = UnionRule::of(new IsString())
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
        $rule = UnionRule::of(new IsString());
        $rule->setMessage(UnionRule::ERR_MESSAGE, 'my custom msg');

        $result = $this->factory->create($rule)->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my custom msg'],
            $result->getErrors(),
        );
    }
}
