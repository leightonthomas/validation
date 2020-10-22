<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Scalar\Strings;

use LeightonThomas\Validation\Rule\Rule;

/**
 * Check if the input string does (or does not, if configured) match the given regular expression.
 *
 * @extends Rule<string, string>
 */
class Regex extends Rule
{

    public const ERR_NOT_STRING = 0;
    public const ERR_MATCHES = 1;
    public const ERR_DOES_NOT_MATCH = 2;

    private string $pattern;

    private bool $matches;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->matches = true;
        $this->messages = [
            self::ERR_NOT_STRING => 'This value must be of type string.',
            self::ERR_MATCHES => 'This value is invalid.',
            self::ERR_DOES_NOT_MATCH => 'This value is invalid.',
        ];
    }

    public function doesNotMatch(): self
    {
        $this->matches = false;

        return $this;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function shouldMatch(): bool
    {
        return $this->matches;
    }
}
