<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Scalar;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Scalar\IsScalar;
use Validation\Rule\Scalar\Boolean\IsBoolean;
use Validation\Rule\Scalar\Float\IsFloat;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\Scalar\Strings\IsString;

class IsScalarTest extends TestCase
{

    private IsScalar $checker;

    public function setUp(): void
    {
        $this->checker = new IsScalar();
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
