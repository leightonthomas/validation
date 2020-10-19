<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker;

use PHPUnit\Framework\TestCase;
use Validation\Checker\AnythingChecker;
use Validation\Rule\Anything;

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
