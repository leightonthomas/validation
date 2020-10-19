<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker;

use stdClass;
use Tests\Validation\DataProvider\TypeProvider;
use Validation\Checker\StrictEquals;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\StrictEquals as StrictEqualsRule;

class StrictEqualsTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new StrictEquals());
    }

    /**
     * @test
     * @dataProvider notValidProvider
     *
     * @param mixed $expected
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfTheValueIsNotTheSame($expected, $value): void
    {
        $rule = new StrictEqualsRule($expected);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value is invalid.'],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notValidProvider
     *
     * @param mixed $expected
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomErrorIfConfiguredAndIfTheValueIsNotTheSame($expected, $value): void
    {
        $rule = new StrictEqualsRule($expected);
        $rule->setMessage(StrictEqualsRule::ERR_INVALID, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['my msg'],
            $result->getErrors(),
        );
    }

    public function notValidProvider(): iterable
    {
        return [
            [4, 5],
            [4.3, 4.4],
            [4.3, 4.30000001],
            [true, false],
            [false, true],
            ['a', 'b'],
            [[], ['a']],
            [['a'], ['b']],
            [['a'], [1 => 'a']],
            [fn() => 'a', fn() => 'a'],
            [new stdClass(), new stdClass()],
        ];
    }

    /**
     * @test
     * @dataProvider allTypes
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNotAddAnErrorIfTheValueIsTheSame($value): void
    {
        $rule = new StrictEqualsRule($value);

        $result = $this->factory->create($rule)->validate($value);

        self::assertTrue($result->isValid());
        self::assertEquals(
            [],
            $result->getErrors(),
        );
    }

    public function allTypes(): iterable
    {
        yield from TypeProvider::getTypes();
    }
}
