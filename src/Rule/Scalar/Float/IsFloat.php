<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Scalar\Float;

use LeightonThomas\Validation\Rule\Scalar\IsScalar;

/**
 * Check if the input is a float.
 *
 * @extends IsScalar<float>
 */
class IsFloat extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_FLOAT);
    }
}
