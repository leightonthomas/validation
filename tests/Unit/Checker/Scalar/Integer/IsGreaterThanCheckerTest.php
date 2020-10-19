<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Scalar\Integer;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Scalar\Integer\IsGreaterThanChecker;
use Validation\Rule\Scalar\Integer\IsGreaterThan;

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
