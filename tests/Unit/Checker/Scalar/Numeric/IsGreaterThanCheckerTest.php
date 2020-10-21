<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Scalar\Numeric;

use LeightonThomas\Validation\Checker\Scalar\Numeric\IsGreaterThanChecker;
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;
use PHPUnit\Framework\TestCase;

class IsGreaterThanCheckerTest extends TestCase
{

    private IsGreaterThanChecker $checker;

    public function setUp(): void
    {
        $this->checker = new IsGreaterThanChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                IsGreaterThan::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
