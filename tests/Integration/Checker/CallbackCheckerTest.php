<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker;

use LeightonThomas\Validation\Checker\CallbackChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Callback;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

class CallbackCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new CallbackChecker($this->factory));
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillRunTheCallback(): void
    {
        $rule = new Callback(
            function ($input, ValidationResult $result, ValidatorFactory $factory): void {
                $result->addError('hello');
                self::assertSame('abc', $input);
                self::assertSame($factory, $this->factory);
            },
        );

        $result = $this->factory->create($rule)->validate('abc');

        self::assertFalse($result->isValid());
        self::assertEquals(
            ['hello'],
            $result->getErrors(),
        );
    }
}
