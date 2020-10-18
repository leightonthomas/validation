<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Arrays;

use Tests\Validation\DataProvider\TypeProvider;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Anything;
use Validation\Checker\Arrays\IsArray;
use Validation\Checker\Arrays\IsDefinedArray;
use Validation\Checker\Combination\Union;
use Validation\Checker\Scalar\IsScalar;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Arrays\IsDefinedArray as IsDefinedArrayRule;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

use function is_array;

class IsDefinedArrayTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalar());
        $this->factory->register(new Union($this->factory));
        $this->factory->register(new IsArray($this->factory));
        $this->factory->register(new IsDefinedArray($this->factory));
        $this->factory->register(new Anything());
    }

    /**
     * @test
     * @dataProvider notArrayProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotAnArray($value): void
    {
        $rule = IsDefinedArrayRule::of('a', new IsString());

        $result = $this->factory->create($rule)->validate($value);

        self::assertEquals(
            ['This value must be an array.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notArrayProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomMessageIfConfiguredIfNotAnArray($value): void
    {
        $rule = IsDefinedArrayRule::of('a', new IsString());
        $rule->setMessage(IsDefinedArrayRule::ERR_NOT_ARRAY, 'my custom message');

        $result = $this->factory->create($rule)->validate($value);

        self::assertEquals(
            ['my custom message'],
            $result->getErrors(),
        );
    }

    public function notArrayProvider(): iterable
    {
        foreach (TypeProvider::getTypes() as $name => [$value]) {
            if (is_array($value)) {
                continue;
            }

            yield $name => [$value];
        }
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorsForMissingKeysWhenOnlyOnePairProvided(): void
    {
        $input = [];
        $rule = IsDefinedArrayRule::of('a', new IsInteger());

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals(
            [
                'a' => ['This key is missing.'],
            ],
            $result->getErrors(),
        );
        self::assertFalse($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorsForMissingKeysWhenMultiplePairsProvided(): void
    {
        $input = [];
        $rule = IsDefinedArrayRule::of('a', new IsInteger())->and('b', new IsString());

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals(
            [
                'a' => ['This key is missing.'],
                'b' => ['This key is missing.'],
            ],
            $result->getErrors(),
        );
        self::assertFalse($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillUseCustomMessageForMissingKeyIfProvided(): void
    {
        $input = [];
        $rule = IsDefinedArrayRule::of('a', new IsInteger())->setMessage(IsDefinedArrayRule::ERR_KEY_MISSING, 'my msg');

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals(
            [
                'a' => ['my msg'],
            ],
            $result->getErrors(),
        );
        self::assertFalse($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorsForInvalidValuesWhenOnlyOnePairProvided(): void
    {
        $input = ['a' => 'b'];
        $rule = IsDefinedArrayRule::of('a', new IsInteger());

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals(
            [
                'a' => ['This value must be of type integer.'],
            ],
            $result->getErrors(),
        );
        self::assertFalse($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorsForInvalidValuesWhenMultiplePairsProvided(): void
    {
        $input = ['a' => 'aaaaa', 'b' => 11111];
        $rule = IsDefinedArrayRule::of('a', new IsInteger())->and('b', new IsString());

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals(
            [
                'a' => ['This value must be of type integer.'],
                'b' => ['This value must be of type string.'],
            ],
            $result->getErrors(),
        );
        self::assertFalse($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddErrorsForValidValuesWhenOnlyOnePairProvided(): void
    {
        $input = ['a' => 1111];
        $rule = IsDefinedArrayRule::of('a', new IsInteger());

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals([], $result->getErrors());
        self::assertTrue($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddErrorsForValidValuesWhenMultiplePairsProvided(): void
    {
        $input = ['a' => 1111, 'b' => 'bbbbbb'];
        $rule = IsDefinedArrayRule::of('a', new IsInteger())->and('b', new IsString());

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals([], $result->getErrors());
        self::assertTrue($result->isValid());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddNestedErrors(): void
    {
        $input = ['a' => []];

        $rule = IsDefinedArrayRule::of(
            'a',
            IsDefinedArrayRule::of('b', new IsString()),
        );

        $result = $this->factory->create($rule)->validate($input);

        self::assertEquals(
            [
                'a' => [
                    'b' => [
                        'This key is missing.',
                    ],
                ],
            ],
            $result->getErrors(),
        );
        self::assertFalse($result->isValid());
    }
}
