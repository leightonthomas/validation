# Custom Error Messages
Rules have a `setMessage` method which lets you override a default error message.

Every Rule has a set of `ERR_*` constants available which reference specific error messages.

Some Rules have basic templating available for error messages.

## Example

```php
use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;

$rule = (new IsGreaterThan(4))
    ->setMessage(IsGreaterThan::ERR_LESS_THAN, 'oh no this must be more than {{value}}!')
    ->setMessage(IsGreaterThan::ERR_IS_EQUAL, 'this cannot be equal to {{value}}!')
;
```
