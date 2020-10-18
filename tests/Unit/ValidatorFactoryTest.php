<?php

declare(strict_types=1);

namespace Tests\Validation\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Validation\Checker\Checker;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Rule;
use Validation\Rule\Scalar\Strings\IsString;
use Validation\ValidationResult;
use Validation\ValidatorFactory;

use function get_class;

class ValidatorFactoryTest extends TestCase
{

    private ValidatorFactory $factory;

    public function setUp(): void
    {
        $this->factory = new ValidatorFactory();
    }

    /**
     * @test
     */
    public function createWillThrowIfNoCheckersRegistered(): void
    {
        $this->expectException(NoCheckersRegistered::class);

        $this->factory->create(new IsString());
    }

    /**
     * @test
     */
    public function createWillReturnAValidatorThatWillRunTheRelevantCheckers(): void
    {
        $inputData = 'abc123';

        $rule = new class extends Rule {

            public function __construct()
            {
                $this->messages = [];
            }

            public function getMessages(): array
            {
                return [];
            }

            public function setMessage(int $type, string $newMessage): Rule
            {
                return $this;
            }
        };

        /** @var MockObject&Checker $checker1 */
        $checker1 = $this
            ->getMockBuilder(Checker::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        /** @var MockObject&Checker $checker2 */
        $checker2 = $this
            ->getMockBuilder(Checker::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $checker1
            ->expects(self::once())
            ->method('check')
            ->with($inputData, $rule, self::isInstanceOf(ValidationResult::class))
        ;

        $checker1
            ->expects(self::once())
            ->method('canCheck')
            ->willReturn([get_class($rule)])
        ;

        $checker2
            ->expects(self::once())
            ->method('check')
            ->with($inputData, $rule, self::isInstanceOf(ValidationResult::class))
        ;

        $checker2
            ->expects(self::once())
            ->method('canCheck')
            ->willReturn([get_class($rule)])
        ;

        $this->factory->register($checker1);
        $this->factory->register($checker2);

        $validator = $this->factory->create($rule);
        $validator->validate($inputData);
    }
}
