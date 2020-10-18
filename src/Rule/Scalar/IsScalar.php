<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar;

use Validation\Rule\Rule;

/**
 * @template T as scalar
 *
 * @psalm-type _ScalarIdentifier = self::SCALAR_*
 *
 * @implements Rule<mixed, T>
 */
abstract class IsScalar implements Rule
{

    public const ERR_MESSAGE = 0;

    public const SCALAR_STRING = 'string';
    public const SCALAR_INT = 'integer';
    public const SCALAR_BOOLEAN = 'boolean';
    public const SCALAR_FLOAT = 'double';

    /**
     * @var string[]
     * @psalm-var array<int, string>
     */
    private array $messages;

    /**
     * @var string
     * @psalm-var _ScalarIdentifier
     */
    private string $type;

    /**
     * @param string $type
     * @psalm-param _ScalarIdentifier $type
     */
    protected function __construct(string $type)
    {
        $this->type = $type;
        $this->messages = [
            self::ERR_MESSAGE => "This value must be of type {{ expectedType }}.",
        ];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     * @psalm-param self::ERR_* $type
     */
    public function setMessage(int $type, string $newMessage): self
    {
        $this->messages[$type] = $newMessage;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
