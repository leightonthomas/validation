<?php

declare(strict_types=1);

namespace Validation\Rule;

/**
 * @extends Rule<mixed, mixed>
 */
class Anything extends Rule
{

    public function __construct()
    {
        $this->messages = [];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessage(int $type, string $newMessage): self
    {
        return $this;
    }
}
