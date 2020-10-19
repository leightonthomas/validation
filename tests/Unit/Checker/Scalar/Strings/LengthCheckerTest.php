<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Scalar\Strings;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Scalar\Strings\LengthChecker;
use Validation\Rule\Scalar\Strings\Length;

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
