<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Arrays;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Arrays\IsArrayChecker;
use Validation\Rule\Arrays\IsArray;
use Validation\ValidatorFactory;

class IsArrayCheckerTest extends TestCase
{

    private IsArrayChecker $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new IsArrayChecker($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [IsArray::class],
            $this->checker->canCheck(),
        );
    }
}
