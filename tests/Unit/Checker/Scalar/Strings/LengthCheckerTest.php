<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Scalar\Strings;

use LeightonThomas\Validation\Checker\Scalar\Strings\LengthChecker;
use LeightonThomas\Validation\Rule\Scalar\Strings\Length;
use PHPUnit\Framework\TestCase;

class LengthCheckerTest extends TestCase
{

    private LengthChecker $checker;

    public function setUp(): void
    {
        $this->checker = new LengthChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                Length::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
