<?php

declare(strict_types=1);

namespace Tests\LeightonThomas\Validation\Integration\Checker;

use LeightonThomas\Validation\ValidatorFactory;
use PHPUnit\Framework\TestCase;

abstract class CheckerTest extends TestCase
{

    protected ValidatorFactory $factory;

    public function setUp(): void
    {
        $this->factory = new ValidatorFactory();
    }
}
