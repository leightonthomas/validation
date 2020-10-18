<?php

declare(strict_types=1);

namespace Validation\Rule\Scalar\Integer;

use Validation\Rule\Scalar\IsScalar;

/**
 * @extends IsScalar<int>
 */
class IsInteger extends IsScalar
{

    public function __construct()
    {
        parent::__construct(IsScalar::SCALAR_INT);
    }
}
