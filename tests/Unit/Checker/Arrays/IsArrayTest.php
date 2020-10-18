<?php

declare(strict_types=1);

namespace Tests\Validation\Unit\Checker\Arrays;

use PHPUnit\Framework\MockObject\MockObject;
use Validation\Checker\Arrays\IsArray;
use PHPUnit\Framework\TestCase;
use Validation\Rule\Arrays\IsArray as IsArrayRule;
use Validation\ValidatorFactory;

class IsArrayTest extends TestCase
{

    private IsArray $checker;

    public function setUp(): void
    {
        /** @var MockObject&ValidatorFactory $factory */
        $factory = $this
            ->getMockBuilder(ValidatorFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->checker = new IsArray($factory);
    }

    /**
     * @test
     */
    public function itWillBeConfiguredForTheCorrectChecks(): void
    {
        self::assertEquals(
            [IsArrayRule::class],
            $this->checker->canCheck(),
        );
    }
}
