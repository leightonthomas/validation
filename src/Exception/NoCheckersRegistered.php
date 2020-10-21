<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Exception;

use Exception;
use LeightonThomas\Validation\Rule\Rule;

class NoCheckersRegistered extends Exception
{

    /**
     * @param string $ruleFqcn
     * @psalm-param class-string<Rule> $ruleFqcn
     */
    public function __construct(string $ruleFqcn)
    {
        parent::__construct("There are no checkers registered that support '{$ruleFqcn}'.");
    }
}
