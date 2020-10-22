<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Scalar\Strings;

use LeightonThomas\Validation\Checker\Scalar\Strings\RegexChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Scalar\Strings\Regex;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

use function is_string;

class RegexCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new RegexChecker());
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
        $rule = new Regex('/hello_\d+/');

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
        $rule = new Regex('/hello_\d+/');
        $rule->setMessage(Regex::ERR_NOT_STRING, 'my msg');

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
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTheInputMatchesButShouldNot(): void
    {
        $rule = new Regex('/hello_\d+/');
        $rule->doesNotMatch();

        $result = $this->factory->create($rule)->validate('hello_4');

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
    public function itWillAddCustomErrorIfConfiguredIfTheInputMatchesButShouldNot(): void
    {
        $rule = new Regex('/hello_\d+/');
        $rule->doesNotMatch();
        $rule->setMessage(Regex::ERR_MATCHES, 'my msg');

        $result = $this->factory->create($rule)->validate('hello_4');

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddErrorIfTheInputDoesNotMatchButShould(): void
    {
        $rule = new Regex('/hello_\d+/');

        $result = $this->factory->create($rule)->validate('hi');

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
    public function itWillAddCustomErrorIfConfiguredIfTheInputDoesNotMatchButShould(): void
    {
        $rule = new Regex('/hello_\d+/');
        $rule->setMessage(Regex::ERR_DOES_NOT_MATCH, 'my msg');

        $result = $this->factory->create($rule)->validate('hi');

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfTheInputMatchesWhenExpectedTo(): void
    {
        $rule = new Regex('/hello_\d+/');

        $result = $this->factory->create($rule)->validate('hello_4');

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfTheInputDoesNotMatchWhenExpectedNotTo(): void
    {
        $rule = new Regex('/hello_\d+/');
        $rule->doesNotMatch();

        $result = $this->factory->create($rule)->validate('hi');

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }
}
