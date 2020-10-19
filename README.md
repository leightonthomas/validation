# Validation (TODO change me)

This library provides composable type-safe validation rules that work great with Psalm.

## Installation

!!!!TODO this should be the correct package name!!!!
1. Install the package `composer require leightonthomas/validation`
1. [Enable the Psalm plugin](https://psalm.dev/docs/running_psalm/plugins/using_plugins/) `vendor/bin/psalm-plugin enable leightonthomas/validation`

Without the plugin, you won't receive for full typing for certain things (e.g. object-like arrays using the `IsDefinedArray` rule), and they may default to less-specific types.

## Usage
### Basic Usage

```php
<?php

use Validation\Checker\Combination\Compose;
use Validation\Checker\Scalar\IsScalar;
use Validation\Rule\Combination\Union;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\StrictEquals;
use Validation\ValidatorFactory;
use Validation\Checker\Arrays\IsDefinedArray;

// Set up the ValidatorFactory and register all Checkers that are going to be used
$factory = new ValidatorFactory();
$factory->register(new IsDefinedArray($factory));
$factory->register(new IsScalar());
$factory->register(new StrictEquals());
$factory->register(new Compose($factory));

$moneyAmount = \Validation\Rule\Combination\Compose::from(new IsInteger())
    ->and(new IsGreaterThan(0))
;

$myRule = \Validation\Rule\Arrays\IsDefinedArray::of(
    'currency',
    Union::of(new StrictEquals('GBP'))->or(new StrictEquals('USD')), 
)
    ->and(
        'amount', 
        new IsInteger()
    )
;
```

## TODO

* Do a pass over the checkers to make sure they still validate the correct type
    * For cases where someone is not using the static analysis features
* Finish off basic rules
    * Multibyte string support
* Set up Travis
* Write some examples
* Add readme section about why, problems it solves
* Test project by requiring on another one and installing plugin - does it work correctly?
    * Write up some validators for known API routes, see if any issues arise
* Investigate PHPStan support

