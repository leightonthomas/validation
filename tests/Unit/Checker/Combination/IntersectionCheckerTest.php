<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Combination;

use LeightonThomas\Validation\Checker\Combination\IntersectionChecker;
use LeightonThomas\Validation\Rule\Combination\Intersection;
use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IntersectionCheckerTest extends TestCase
{

    private IntersectionChecker $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new IntersectionChecker($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [Intersection::class],
            $this->checker->canCheck(),
        );
    }
}
