<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Scalar;

use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\Float\IsFloat;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use PHPUnit\Framework\TestCase;

class IsScalarCheckerTest extends TestCase
{

    private IsScalarChecker $checker;

    public function setUp(): void
    {
        $this->checker = new IsScalarChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                IsString::class,
                IsInteger::class,
                IsFloat::class,
                IsBoolean::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
