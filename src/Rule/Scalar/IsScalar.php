<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar;

use Validation\Rule\Rule;

/**
 * @template T as scalar
 *
 * @psalm-type _ScalarIdentifier = self::SCALAR_*
 *
 * @extends Rule<mixed, T>
 */
abstract class IsScalar extends Rule
{

    public const ERR_MESSAGE = 0;

    public const SCALAR_STRING = 'string';
    public const SCALAR_INT = 'integer';
    public const SCALAR_BOOLEAN = 'boolean';
    public const SCALAR_FLOAT = 'double';

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

    public function getType(): string
    {
        return $this->type;
    }
}
