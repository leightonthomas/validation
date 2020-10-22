<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Rule\Scalar\Integer;

use LeightonThomas\Validation\Rule\Scalar\IsScalar;

/**
 * Check if the input is an integer.
 *
 * @extends IsScalar<int>
 */
class IsInteger extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_INT);
    }
}
