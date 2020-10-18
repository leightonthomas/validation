<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Arrays;

use PHPUnit\Framework\MockObject\MockObject;
use Validation\Checker\Arrays\IsDefinedArray;
use PHPUnit\Framework\TestCase;
use Validation\Rule\Arrays\IsDefinedArray as IsDefinedArrayRule;
use Validation\ValidatorFactory;

class IsDefinedArrayTest extends TestCase
{

    private IsDefinedArray $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new IsDefinedArray($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [IsDefinedArrayRule::class],
            $this->checker->canCheck(),
        );
    }
}
