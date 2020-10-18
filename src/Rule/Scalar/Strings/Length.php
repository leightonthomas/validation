<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Strings;

use InvalidArgumentException;
use Validation\Rule\Rule;

/**
 * @implements Rule<string, string>
 */
class Length implements Rule
{

    public const ERR_TOO_SHORT = 0;
    public const ERR_TOO_LONG = 1;
    public const ERR_NOT_STRING = 2;

    /**
     * @var string[]
     * @psalm-var array<int, string>
     */
    private array $messages;

    private ?int $min;

    private ?int $max;

    /**
     * @param int|null $min
     * @param int|null $max
     *
     * @throws InvalidArgumentException if both $min and $max are NULL.
     */
    public function __construct(
        ?int $min = null,
        ?int $max = null
    ) {
        if (($min === null) && ($max === null)) {
            throw new InvalidArgumentException("You must provide either a minimum or a maximum.");
        }

        $this->min = $min;
        $this->max = $max;

        $this->messages = [
            self::ERR_TOO_SHORT => 'This value must be at least {{ minimum }} character(s) long.',
            self::ERR_TOO_LONG => 'This value must be at most {{ maximum }} character(s) long.',
            self::ERR_NOT_STRING => 'This value must be of type string.',
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

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
