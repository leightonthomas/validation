<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Float;

use Validation\Rule\Scalar\IsScalar;

/**
 * @extends IsScalar<float>
 */
class IsFloat extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_FLOAT);
    }
}
