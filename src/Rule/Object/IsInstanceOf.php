<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Object;

use LeightonThomas\Validation\Rule\Rule;

/**
 * @template I of object
 * @template O of object
 *
 * @extends Rule<I, O>
 */
class IsInstanceOf extends Rule
{

    public const ERR_NOT_INSTANCE = 0;
    public const ERR_NOT_OBJECT = 1;

    /**
     * @var string
     * @psalm-var class-string<O>
     */
    private string $fqcn;

    /**
     * @param string $fqcn
     * @psalm-param class-string<O> $fqcn
     */
    public function __construct(string $fqcn)
    {
        $this->fqcn = $fqcn;
        $this->messages = [
            self::ERR_NOT_OBJECT => 'This value must be an object.',
            self::ERR_NOT_INSTANCE => 'This value must be an instance of {{ expected }}.',
        ];
    }

    /**
     * @return string
     * @psalm-var class-string<O>
     */
    public function getFqcn(): string
    {
        return $this->fqcn;
    }
}
