<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Scalar\Strings;

use LeightonThomas\Validation\Rule\Scalar\IsScalar;

/**
 * Check if the input is a string.
 *
 * @extends IsScalar<string>
 */
class IsString extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_STRING);
    }
}
