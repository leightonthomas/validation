<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Combination;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Combination\Intersection;
use Validation\Rule\Combination\Intersection as IntersectionRule;
use Validation\ValidatorFactory;

class IntersectionTest extends TestCase
{

    private Intersection $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new Intersection($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [IntersectionRule::class],
            $this->checker->canCheck(),
        );
    }
}
