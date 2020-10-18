<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Strings;

use Validation\Rule\Scalar\IsScalar;

/**
 * @extends IsScalar<string>
 */
class IsString extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_STRING);
    }
}
