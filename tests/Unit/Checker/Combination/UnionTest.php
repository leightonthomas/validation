<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Combination;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Combination\Union;
use Validation\Rule\Combination\Union as UnionRule;
use Validation\ValidatorFactory;

class UnionTest extends TestCase
{

    private Union $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new Union($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [UnionRule::class],
            $this->checker->canCheck(),
        );
    }
}
