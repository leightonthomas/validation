<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Scalar\Boolean;

use LeightonThomas\Validation\Rule\Scalar\IsScalar;

/**
 * @extends IsScalar<bool>
 */
class IsBoolean extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_BOOLEAN);
    }
}
