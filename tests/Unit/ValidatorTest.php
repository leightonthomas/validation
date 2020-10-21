<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit;

use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use LeightonThomas\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillRunCheckerOnValidateAndReturnTheResult(): void
    {
        $validator = new Validator(
            new IsString(),
            [new IsScalarChecker()]
        );

        $result = $validator->validate(4);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['This value must be of type string.'],
            $result->getErrors(),
        );
    }
}
