<?php

declare(strict_types=1);

namespace Tests\Validation\DataProvider;

use stdClass;

class TypeProvider
{

    /**
     * @return iterable
     * @psalm-return array<string, array{0: mixed, 1: string}>
     */
    public static function getTypes(): iterable
    {
        return [
            'array' => [[], 'array'],
            'integer' => [2, 'integer'],
            'float' => [4.1, 'double'],
            'bool (true)' => [true, 'boolean'],
            'bool (false)' => [false, 'boolean'],
            'null' => [null, 'NULL'],
            'string' => ['hello', 'string'],
            'empty string' => ['', 'string'],
            'stdClass' => [new stdClass(), 'object'],
            'function' => [fn() => 'hello', 'object'],
        ];
    }
}
