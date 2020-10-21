<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Scalar\Numeric;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Scalar\Numeric\IsGreaterThanChecker;
use Validation\Rule\Scalar\Numeric\IsGreaterThan;

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
