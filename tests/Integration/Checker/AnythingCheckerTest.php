<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker;

use LeightonThomas\Validation\Checker\AnythingChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Anything;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;

class AnythingCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new AnythingChecker());
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
        $result = $this->factory->create(new Anything())->validate($value);

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
