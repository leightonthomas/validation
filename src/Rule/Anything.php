<?php

declare(strict_types=1);

namespace Validation\Rule;

/**
 * @implements Rule<mixed, mixed>
 */
class Anything implements Rule
{

    public function getMessages(): array
    {
        return [];
    }

    public function setMessage(int $type, string $newMessage): self
    {
        return $this;
    }
}
