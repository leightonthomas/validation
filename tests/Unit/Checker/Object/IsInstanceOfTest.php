<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Object;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Object\IsInstanceOf;
use Validation\Rule\Object\IsInstanceOf as IsInstanceOfRule;

class IsInstanceOfTest extends TestCase
{

    private IsInstanceOf $checker;

    public function setUp(): void
    {
        $this->checker = new IsInstanceOf();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                IsInstanceOfRule::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
