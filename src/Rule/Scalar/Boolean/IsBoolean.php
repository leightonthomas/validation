<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Boolean;

use Validation\Rule\Scalar\IsScalar;

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
