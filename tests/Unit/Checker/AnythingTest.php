<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Anything;
use Validation\Rule\Anything as AnythingRule;

class AnythingTest extends TestCase
{

    private Anything $checker;

    public function setUp(): void
    {
        $this->checker = new Anything();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                AnythingRule::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
