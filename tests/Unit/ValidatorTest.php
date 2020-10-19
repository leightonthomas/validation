<?php

declare(strict_types=1);

namespace Tests\Validation\Unit;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Scalar\IsScalarChecker;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Scalar\Strings\IsString;
use Validation\Validator;

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
