<?php

declare(strict_types=1);

namespace Validation\Rule;

/**
 * @template I
 * @template O
 */
abstract class Rule
{

    /**
     * @var string[]
     * @psalm-var array<int, string>
     */
    protected array $messages;

    /**
     * @return string[]
     *
     * @psalm-return array<int, string>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param int $type
     * @param string $newMessage
     *
     * @return self
     * @psalm-return $this
     */
    public function setMessage(int $type, string $newMessage): self
    {
        $this->messages[$type] = $newMessage;

        return $this;
    }
}
