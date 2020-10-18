<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker;

use Tests\Validation\DataProvider\TypeProvider;
use Validation\Checker\Anything;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Anything as AnythingRule;

class AnythingTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new Anything());
    }

    /**
     * @test
     * @dataProvider allTypes
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillNeverAddAnError($value): void
    {
        $result = $this->factory->create(new AnythingRule())->validate($value);

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
