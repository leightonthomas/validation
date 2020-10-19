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

use Validation\Checker\Combination\ComposeChecker;
use Validation\Checker\Combination\UnionChecker;
use Validation\Checker\Scalar\Integer\IsGreaterThanChecker;
use Validation\Checker\Scalar\IsScalarChecker;
use Validation\Checker\StrictEqualsChecker;
use Validation\Rule\Arrays\IsDefinedArray;
use Validation\Rule\Combination\Compose;
use Validation\Rule\Combination\Union;
use Validation\Rule\Scalar\Integer\IsGreaterThan;
use Validation\Rule\Scalar\Integer\IsInteger;
use Validation\Rule\StrictEquals;
use Validation\ValidatorFactory;
use Validation\Checker\Arrays\IsDefinedArrayChecker;

// Set up the ValidatorFactory and register all available Checkers
$factory = new ValidatorFactory();
$factory->register(new IsDefinedArrayChecker($factory));
$factory->register(new IsScalarChecker());
$factory->register(new StrictEqualsChecker());
$factory->register(new ComposeChecker($factory));
$factory->register(new IsGreaterThanChecker());
$factory->register(new UnionChecker($factory));

// Compose rules
$isCurrencyCode = Union::of(new StrictEquals('GBP'))
    ->or(new StrictEquals('USD'))
    ->setMessage(Union::ERR_MESSAGE, 'This must be a valid currency code.')
;

$isMoneyAmount = Compose::from(new IsInteger())
    ->and(new IsGreaterThan(0))
;

$myRule = IsDefinedArray::of('currency', $isCurrencyCode)
    ->and('amount', $isMoneyAmount)
;

// Create a reusable validator for the Rule to validate against
$validator = $factory->create($myRule);

// The type of this is ValidationResult<array{currency: string(GBP)|string(USD), amount: int}>
$result = $validator->validate([]);

if ($result->isValid()) {
    // This will be typed as array{currency: string(GBP)|string(USD), amount: int}
    $outputValue = $result->getValue();
} else {
    /**
     * Output:
     * [
     *     'currency' => [
     *         'This must be a valid currency code.',
     *     ],
     *     'amount' => [
     *         'This value must be of type integer.',
     *     ],
     * ]
     */
    var_dump($result->getErrors());
}
```

## TODO

* Do a pass over the checkers to make sure they still validate the correct type
    * For cases where someone is not using the static analysis features
* Add doc-blocks that explain what they do and are for
* Finish off basic rules
    * LessThan (int)
    * Optional keys for IsDefinedArray
    * Multibyte string support
* Set up Travis
* Write some examples
* Add readme section about why, problems it solves
* Test project by requiring on another one and installing plugin - does it work correctly?
    * Write up some validators for known API routes, see if any issues arise
* Investigate PHPStan support
* Do a Psalm pass over the tests, get that Psalm'd up
