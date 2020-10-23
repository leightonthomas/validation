# Validation

![Tests](https://github.com/leightonthomas/validation/workflows/Tests/badge.svg?branch=master)

This library provides composable type-safe validation that works great with Psalm.

A stable version has not yet been reached so things are still subject to change.

## Installation

1. Install the validator package itself `composer require leightonthomas/validation`
1. Install the Psalm plugin `composer require --dev leightonthomas/validation-psalm-plugin`
1. [Enable the Psalm plugin](https://psalm.dev/docs/running_psalm/plugins/using_plugins/) `psalm-plugin enable leightonthomas/validation-psalm-plugin`

Without the plugin, you won't receive for full typing for certain rules (e.g. dynamically created object-like arrays), and they may default to less-specific types.

## Usage
1. Create a `LeightonThomas\Validation\ValidatorFactory` instance
1. Register all checkers with the factory
1. Compose rules
1. Create a validator for your rules with the validator factory

### Example

This example assumes the Psalm plugin is installed and enabled.

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
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\StrictEquals;
use LeightonThomas\Validation\ValidatorFactory;
use LeightonThomas\Validation\Checker\Arrays\IsDefinedArrayChecker;

// Set up the ValidatorFactory and register checkers
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
    ->andMaybe('time', new IsInstanceOf(DateTimeInterface::class))
;

// Create a reusable validator for the Rule to validate against
$validator = $factory->create($myRule);

$result = $validator->validate([]);

if ($result->isValid()) {
    // This will be typed as array{amount: int, currency: string(GBP)|string(USD), time?: DateTimeInterface}
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
