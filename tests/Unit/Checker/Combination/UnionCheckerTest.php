<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Combination;

use LeightonThomas\Validation\Checker\Combination\UnionChecker;
use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnionCheckerTest extends TestCase
{

    private UnionChecker $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new UnionChecker($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [Union::class],
            $this->checker->canCheck(),
        );
    }
}
