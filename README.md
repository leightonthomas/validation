# Validation (TODO change me)

This library provides composable type-safe validation rules that work great with Psalm.

## Installation

!!!!TODO this should be the correct package name!!!!

```
composer require leightonthomas/validation

vendor/bin/psalm-plugin enable leightonthomas/validation
```

Without the plugin, you won't receive for full typing for certain things, e.g. object-like arrays using
the `IsDefinedArray` rule.

## TODO

* Do a pass over the checkers to make sure they still validate the correct type
    * For cases where someone is not using the static analysis features
* Finish off basic rules
    * Add "Intersection" rule, `Rule<mixed, A&B&C>`
    * Add "IsNull" rule, `Rule<mixed, null>`
    * Add "Constant" rule, `Rule<mixed, T>`
    * Multibyte string support
* Write some examples
* Add readme section about why, problems it solves
* Test project by requiring on another one and installing plugin - does it work correctly?
    * Write up some validators for known API routes, see if any issues arise
* `abstract class Rule` for messages instead of interface, so message stuff is reusable?
* Investigate PHPStan support
