<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Arrays;

use LeightonThomas\Validation\Checker\AnythingChecker;
use LeightonThomas\Validation\Checker\Arrays\IsArrayChecker;
use LeightonThomas\Validation\Checker\Arrays\IsDefinedArrayChecker;
use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

use function is_array;

class IsDefinedArrayCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsScalarChecker());
        $this->factory->register(new IsArrayChecker($this->factory));
        $this->factory->register(new IsDefinedArrayChecker($this->factory));
        $this->factory->register(new AnythingChecker());
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
        $rule = IsDefinedArray::of('a', new IsString());

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
        $rule = IsDefinedArray::of('a', new IsString());
        $rule->setMessage(IsDefinedArray::ERR_NOT_ARRAY, 'my custom message');

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
        $rule = IsDefinedArray::of('a', new IsInteger());

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
        $rule = IsDefinedArray::of('a', new IsInteger())->and('b', new IsString());

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
        $rule = IsDefinedArray::of('a', new IsInteger())->setMessage(IsDefinedArray::ERR_KEY_MISSING, 'my msg');

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
    public function itWillNotAddErrorsForMissingKeyIfOptionalWhenOnlyOneKey(): void
    {
        $input = [];
        $rule = IsDefinedArray::ofMaybe('a', new IsInteger());

        $result = $this->factory->create($rule)->validate($input);

        self::assertTrue($result->isValid());
        self::assertEquals(
            [],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddErrorsForMissingKeyIfOptionalWhenOnlyMultipleKeys(): void
    {
        $input = [];
        $rule = IsDefinedArray::ofMaybe('a', new IsInteger())
            ->andMaybe('b', new IsInteger())
            ->and('c', new IsInteger())
        ;

        $result = $this->factory->create($rule)->validate($input);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['c' => ['This key is missing.']],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorsForInvalidValuesWhenOnlyOnePairProvided(): void
    {
        $input = ['a' => 'b'];
        $rule = IsDefinedArray::of('a', new IsInteger());

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
    public function itWillAddErrorsForInvalidValuesWhenOnlyOneOptionalPairProvided(): void
    {
        $input = ['a' => 'b'];
        $rule = IsDefinedArray::ofMaybe('a', new IsInteger());

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
        $rule = IsDefinedArray::of('a', new IsInteger())->andMaybe('b', new IsString());

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
        $rule = IsDefinedArray::of('a', new IsInteger());

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
        $rule = IsDefinedArray::of('a', new IsInteger())->andMaybe('b', new IsString());

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

        $rule = IsDefinedArray::of(
            'a',
            IsDefinedArray::of('b', new IsString()),
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
