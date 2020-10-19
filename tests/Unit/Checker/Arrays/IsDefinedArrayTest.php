<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Arrays;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Arrays\IsDefinedArrayChecker;
use Validation\Rule\Arrays\IsDefinedArray;
use Validation\ValidatorFactory;

class IsDefinedArrayTest extends TestCase
{

    private IsDefinedArrayChecker $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new IsDefinedArrayChecker($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [IsDefinedArray::class],
            $this->checker->canCheck(),
        );
    }
}
