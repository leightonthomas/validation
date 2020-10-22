<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule;

/**
 * This rule will allow any value and always outputs the mixed type.
 *
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
        return [];
    }
}
