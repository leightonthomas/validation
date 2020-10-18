<?php

declare(strict_types=1);

namespace Tests\Validation\Integration\Checker;

use PHPUnit\Framework\TestCase;
use Validation\ValidatorFactory;

abstract class CheckerTest extends TestCase
{

    protected ValidatorFactory $factory;

    public function setUp(): void
    {
        $this->factory = new ValidatorFactory();
    }
}
