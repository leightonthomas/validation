<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Unit\Checker\Arrays;

use LeightonThomas\Validation\Checker\Arrays\IsDefinedArrayChecker;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IsDefinedArrayCheckerTest extends TestCase
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
