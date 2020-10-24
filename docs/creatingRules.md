# Creating Rules
To create a rule, just extend the `Rule` base class and be sure to provide Psalm typing information.

Rules have an input type `I` and the validated output type `O`.

The input type does not have to be `mixed`, but anything else will mean that another Rule has to narrow the type first.

As an example, `Rule<mixed, string>` tells us that the Rule can validate that any value (`mixed`) is a `string`. 
`Rule<string, class-string>` tells us that the Rule can validate that any given `string` is actually a `class-string`.

Each Rule will also need a corresponding `Checker` that actually performs the validation.

## Example

```php
<?php

use LeightonThomas\Validation\Checker\Checker;
use LeightonThomas\Validation\Rule\Rule;
use LeightonThomas\Validation\ValidationResult;

/**
 * @extends Rule<string, string(GBP)|string(USD)>
 */
class IsAcceptableCurrencyCode extends Rule
{
}

/**
 * @implements Checker<IsAcceptableCurrencyCode>
 */
class IsAcceptableCurrencyCodeChecker implements Checker
{

    /**
     * {@inheritdoc}
     * 
     * @param string $value
     */
    public function check($value, Rule $rule, ValidationResult $result) : void
    {
        if (in_array($value, ['USD', 'GBP'], true)) {
            return;
        }
        
        $result->addError('This currency is not yet accepted.');
    }
    
    public function canCheck(): array
    {
        return [IsAcceptableCurrencyCode::class];
    }
}
```

