<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Combination;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Combination\UnionChecker;
use Validation\Rule\Combination\Union;
use Validation\ValidatorFactory;

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
