<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Scalar\Strings;

use PHPUnit\Framework\TestCase;
use Validation\Checker\Scalar\Strings\Length;
use Validation\Rule\Scalar\Strings\Length as LengthRule;

class LengthTest extends TestCase
{

    private Length $checker;

    public function setUp(): void
    {
        $this->checker = new Length();
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                LengthRule::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
