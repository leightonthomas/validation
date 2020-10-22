<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Scalar\Strings;

use LeightonThomas\Validation\Checker\Scalar\Strings\RegexChecker;
use LeightonThomas\Validation\Rule\Scalar\Strings\Regex;
use PHPUnit\Framework\TestCase;

class RegexCheckerTest extends TestCase
{

    private RegexChecker $checker;

    public function setUp(): void
    {
        $this->checker = new RegexChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                Regex::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
