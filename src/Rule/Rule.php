<?php

declare(strict_types=1);

namespace Validation\Rule;

/**
 * @template I
 * @template O
 */
interface Rule
{

    /**
     * @return string[]
     *
     * @psalm-return array<int, string>
     */
    public function getMessages(): array;

    /**
     * @param int $type
     * @param string $newMessage
     *
     * @return self
     * @psalm-return self<I, O>
     */
    public function setMessage(int $type, string $newMessage): self;
}
