<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker\Object;

use stdClass;
use Tests\Validation\DataProvider\TypeProvider;
use Tests\Validation\Integration\Checker\CheckerTest;
use Validation\Checker\Checker;
use Validation\Checker\Object\IsInstanceOf;
use Validation\Exception\NoCheckersRegistered;
use Validation\Rule\Object\IsInstanceOf as IsInstanceOfRule;
use Validation\Rule\Rule;
use Validation\Rule\Scalar\Strings\IsString;
use Validation\ValidatorFactory;

use function is_object;

class IsInstanceOfTest extends CheckerTest
{

    public function setUp(): void
    {
        parent::setUp();

        $this->factory->register(new IsInstanceOf());
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
        $rule = new IsInstanceOfRule(ValidatorFactory::class);

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
        $rule = new IsInstanceOfRule(ValidatorFactory::class);
        $rule->setMessage(IsInstanceOfRule::ERR_NOT_OBJECT, 'my msg');

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
        $rule = new IsInstanceOfRule(ValidatorFactory::class);

        $result = $this->factory->create($rule)->validate(new stdClass());

        self::assertFalse($result->isValid());
        self::assertEquals(
            ["This value must be an instance of Validation\ValidatorFactory."],
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
        $rule = new IsInstanceOfRule(ValidatorFactory::class);
        $rule->setMessage(IsInstanceOfRule::ERR_NOT_INSTANCE, 'my msg');

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
        $rule = new IsInstanceOfRule($fqcn);

        $result = $this->factory->create($rule)->validate($obj);

        self::assertTrue($result->isValid());
        self::assertEquals([], $result->getErrors());
    }

    public function validProvider(): iterable
    {
        return [
            'same' => [ValidatorFactory::class, new ValidatorFactory()],
            'abstract' => [Rule::class, new IsString()],
            'interface' => [Checker::class, new IsInstanceOf()],
        ];
    }
}
