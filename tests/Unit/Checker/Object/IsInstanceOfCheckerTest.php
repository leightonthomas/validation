<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Object;

use LeightonThomas\Validation\Checker\Object\IsInstanceOfChecker;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;
use PHPUnit\Framework\TestCase;

class IsInstanceOfCheckerTest extends TestCase
{

    private IsInstanceOfChecker $checker;

    public function setUp(): void
    {
        $this->checker = new IsInstanceOfChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                IsInstanceOf::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
