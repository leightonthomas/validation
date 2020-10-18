<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Combination;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Combination\Compose;
use Validation\Rule\Combination\Compose as ComposeRule;
use Validation\ValidatorFactory;

class ComposeTest extends TestCase
{

    private Compose $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new Compose($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [ComposeRule::class],
            $this->checker->canCheck(),
        );
    }
}
