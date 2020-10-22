<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker;

use LeightonThomas\Validation\Checker\CallbackChecker;
use LeightonThomas\Validation\Rule\Callback;
use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\TestCase;

class CallbackCheckerTest extends TestCase
{

    private CallbackChecker $checker;

    public function setUp(): void
    {
        $this->checker = new CallbackChecker(new ValidatorFactory());
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [
                Callback::class,
            ],
            $this->checker->canCheck(),
        );
    }
}
