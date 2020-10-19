# Validation (TODO change me)

This library provides composable type-safe validation rules that work great with Psalm.

## Installation

!!!!TODO this should be the correct package name!!!!
1. Install the package `composer require leightonthomas/validation`
1. [Enable the Psalm plugin](https://psalm.dev/docs/running_psalm/plugins/using_plugins/) `vendor/bin/psalm-plugin enable leightonthomas/validation`

Without the plugin, you won't receive for full typing for certain things (e.g. object-like arrays using the `IsDefinedArray` rule), and they may default to less-specific types.

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
