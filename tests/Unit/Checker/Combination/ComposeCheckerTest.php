<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Combination;

use LeightonThomas\Validation\Checker\Combination\ComposeChecker;
use LeightonThomas\Validation\Rule\Combination\Compose;
use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ComposeCheckerTest extends TestCase
{

    private ComposeChecker $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new ComposeChecker($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [Compose::class],
            $this->checker->canCheck(),
        );
    }
}
