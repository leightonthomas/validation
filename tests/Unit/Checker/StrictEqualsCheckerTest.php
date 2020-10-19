<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker;

use PHPUnit\Framework\TestCase;
use Validation\Checker\StrictEqualsChecker;
use Validation\Rule\StrictEquals;

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
