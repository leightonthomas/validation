<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Scalar\Strings;

use Tests\Validation\DataProvider\TypeProvider;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Scalar\Strings\LengthChecker;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Scalar\Strings\Length;

use function is_string;

class LengthCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new LengthChecker());
    }

    /**
     * @test
     * @dataProvider notStringProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotAString($value): void
    {
        $rule = new Length(null, 100);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be of type string.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notStringProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomErrorIfNotAStringAndConfigured($value): void
    {
        $rule = new Length(null, 100);
        $rule->setMessage(Length::ERR_NOT_STRING, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function notStringProvider(): iterable
    {
        foreach (TypeProvider::getTypes() as $typeName => [$type]) {
            if (is_string($type)) {
                continue;
            }

            yield $typeName => [$type];
        }
    }

    /**
     * @test
     * @dataProvider tooShortProvider
     *
     * @param string $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTooShort(string $value): void
    {
        $rule = new Length(3);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be at least 3 character(s) long.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooShortProvider
     *
     * @param string $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomerErrorIfConfiguredAndTooShort(string $value): void
    {
        $rule = new Length(3);
        $rule->setMessage(Length::ERR_TOO_SHORT, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function tooShortProvider(): iterable
    {
        return [
            [''],
            ['a'],
            ['ab'],
        ];
    }

    /**
     * @test
     * @dataProvider tooLongProvider
     *
     * @param string $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTooLong(string $value): void
    {
        $rule = new Length(null, 3);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be at most 3 character(s) long.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider tooLongProvider
     *
     * @param string $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddCustomerErrorIfConfiguredAndTooLong(string $value): void
    {
        $rule = new Length(null, 3);
        $rule->setMessage(Length::ERR_TOO_LONG, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function tooLongProvider(): iterable
    {
        return [
            ['abcd'],
            ['abcde'],
            ['abcdef'],
        ];
    }

    /**
     * @test
     * @dataProvider longerThanMinProvider
     *
     * @param string $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddErrorIfValueLongerThanOrEqualToMin(string $value): void
    {
        $rule = new Length(3);

        $result = $this->factory->create($rule)->validate($value);

        self::assertTrue($result->isValid());
        self::assertEquals(
            [],
            $result->getErrors(),
        );
    }

    public function longerThanMinProvider(): iterable
    {
        return [
            ['abc'],
            ['abcd'],
            ['abcde'],
        ];
    }

    /**
     * @test
     * @dataProvider shorterThanMaxProvider
     *
     * @param string $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddErrorIfValueShorterThanOrEqualToMax(string $value): void
    {
        $rule = new Length(null, 6);

        $result = $this->factory->create($rule)->validate($value);

        self::assertTrue($result->isValid());
        self::assertEquals(
            [],
            $result->getErrors(),
        );
    }

    public function shorterThanMaxProvider(): iterable
    {
        return [
            ['abc'],
            ['abcd'],
            ['abcde'],
            ['abcdef'],
        ];
    }
}
