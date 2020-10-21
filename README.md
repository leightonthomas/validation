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

use LeightonThomas\Validation\Checker\Combination\ComposeChecker;
use LeightonThomas\Validation\Checker\Combination\UnionChecker;
use LeightonThomas\Validation\Checker\Scalar\Numeric\IsGreaterThanChecker;
use LeightonThomas\Validation\Checker\Scalar\IsScalarChecker;
use LeightonThomas\Validation\Checker\StrictEqualsChecker;
use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Combination\Compose;
use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\StrictEquals;
use LeightonThomas\Validation\ValidatorFactory;
use LeightonThomas\Validation\Checker\Arrays\IsDefinedArrayChecker;

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

$result = $validator->validate([]);

if ($result->isValid()) {
    // This will be typed as array{amount: int, currency: string(GBP)|string(USD)}
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

* Add keywords + description to composer
* Add proper version (1.0.0) to composer
* Do a pass over the checkers to make sure they still validate the correct type
    * For cases where someone is not using the static analysis features
* Add doc-blocks that explain what Rules do and are for/what purpose they serve
* Finish off basic rules
    * Callback
    * Multibyte string support (?)
* Set up Travis
* Write some examples
* Add readme section about why, problems it solves
* Test project by requiring on another one and installing plugin - does it work correctly?
    * Write up some validators for known API routes, see if any issues arise
* Investigate PHPStan support
* Do a Psalm pass over the tests, get that Psalm'd up
