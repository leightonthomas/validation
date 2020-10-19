<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker;

use PHPUnit\Framework\TestCase;
use Validation\Checker\StrictEquals;
use Validation\Rule\StrictEquals as StrictEqualsRule;

class StrictEqualsTest extends TestCase
{

    private StrictEquals $checker;

    public function setUp(): void
    {
        $this->checker = new StrictEquals();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                StrictEqualsRule::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
