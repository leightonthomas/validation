<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Object;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Object\IsInstanceOfChecker;
use Validation\Rule\Object\IsInstanceOf;

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
