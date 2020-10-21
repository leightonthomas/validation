<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker;

use LeightonThomas\Validation\Checker\StrictEqualsChecker;
use LeightonThomas\Validation\Rule\StrictEquals;
use PHPUnit\Framework\TestCase;

class StrictEqualsCheckerTest extends TestCase
{

    private StrictEqualsChecker $checker;

    public function setUp(): void
    {
        $this->checker = new StrictEqualsChecker();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                StrictEquals::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
