<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker;

use LeightonThomas\Validation\Checker\AnythingChecker;
use LeightonThomas\Validation\Rule\Anything;
use PHPUnit\Framework\TestCase;

class AnythingCheckerTest extends TestCase
{

    private AnythingChecker $checker;

    public function setUp(): void
    {
        $this->checker = new AnythingChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                Anything::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
