<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker\Object;

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Checker\Object\IsInstanceOfChecker;
use LeightonThomas\Validation\Exception\NoCheckersRegistered;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use LeightonThomas\Validation\ValidatorFactory;
use stdClass;
use Tests\LeightonThomas\Validation\DataProvider\TypeProvider;
use Tests\LeightonThomas\Validation\Integration\Checker\CheckerTest;

use function is_object;

class IsInstanceOfCheckerTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsInstanceOfChecker());
    }

    /**
     * @test
     * @dataProvider notObjectProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotObject($value): void
    {
        $rule = new IsInstanceOf(ValidatorFactory::class);

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be an object."],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider notObjectProvider
     *
     * @param mixed $value
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomErrorMessageIfConfiguredIfNotObject($value): void
    {
        $rule = new IsInstanceOf(ValidatorFactory::class);
        $rule->setMessage(IsInstanceOf::ERR_NOT_OBJECT, 'my msg');

        $result = $this->factory->create($rule)->validate($value);

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["my msg"],
            $result->getErrors(),
        );
    }

    public function notObjectProvider(): iterable
    {
        foreach (TypeProvider::getTypes() as [$type]) {
            if (is_object($type)) {
                continue;
            }

            yield [$type];
        }
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddAnErrorIfNotInstance(): void
    {
        $rule = new IsInstanceOf(ValidatorFactory::class);

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be an instance of LeightonThomas\Validation\ValidatorFactory."],
            $result->getErrors(),
        );
    }

    /**
     * @test
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddACustomErrorIfConfiguredIfNotInstance(): void
    {
        $rule = new IsInstanceOf(ValidatorFactory::class);
        $rule->setMessage(IsInstanceOf::ERR_NOT_INSTANCE, 'my msg');

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["my msg"],
            $result->getErrors(),
        );
    }

    /**
     * @test
     * @dataProvider validProvider
     *
     * @param string $fqcn
     * @param object $obj
     *
     * @psalm-param class-string $fqcn
     *
     * @throws NoCheckersRegistered
     */
    public function itWillAddNoErrorIfInputIsInstance(string $fqcn, object $obj): void
    {
        $rule = new IsInstanceOf($fqcn);

        $result = $this->factory->create($rule)->validate($obj);

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    public function validProvider(): iterable
    {
        return [
            'same' => [ValidatorFactory::class, new ValidatorFactory()],
            'abstract' => [Rule::class, new IsString()],
            'interface' => [Checker::class, new IsInstanceOfChecker()],
        ];
    }
}
