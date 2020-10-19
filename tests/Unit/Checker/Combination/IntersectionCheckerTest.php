<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Combination;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Combination\IntersectionChecker;
use Validation\Rule\Combination\Intersection;
use Validation\ValidatorFactory;

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
