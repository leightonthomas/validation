# Available Rules

## General

### Anything

Accepts any value and performs no validation.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Anything;

$rule = new Anything(); 
```

### Callback

Run the input value of type through the provided callback and letting that perform any validation.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Callback;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
use LeightonThomas\Validation\ValidationResult;
use LeightonThomas\Validation\ValidatorFactory;

/** @psalm-var Callback<mixed, array> $rule */
$rule = new Callback(
    function($value, ValidationResult $result, ValidatorFactory $factory): void {
        // work with the $value however you like
        if (! is_array($value)) {
            return;
        }
        
        // add errors
        $result->addError('some error message');
        
        // create sub-validators and validate those using the factory
        $factory->create(new IsString())->validate($value);
    },
); 
```

### StrictEquals

Validate that the input value `===`s the configured value.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\StrictEquals;

$rule = new StrictEquals(PHP_INT_MAX); 
```

## Arrays

### IsArray

Validate that the input is an array with keys that match a Rule, and values that match a Rule.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Arrays\IsArray;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;

// the default will validate array<array-key, mixed>, essentially just an array of any kind, regardless of types
$defaultRule = new IsArray();
// you can provide a rule for keys, validating to array<int, mixed>
$intKeys = new IsArray(new IsInteger());
// and provide a rule for values, validating to array<int, string>
$intKeysStringValues = new IsArray(new IsInteger(), new IsString());
// or you can just provide a rule for values, validating to array<array-key, string>
$anyKeyStringValues = new IsArray(null, new IsString());
```

### IsDefinedArray

Validate that the input is an array with specific keys and values.

The Psalm plugin is required to get specific type output for this Rule - without it you'll just
receive `IsDefinedArray<array<array-key, mixed>>`.

This Rule will always validate every key.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;

$rule1 = IsDefinedArray::of('foo', new IsString())
    ->andMaybe('bar', new IsInteger())
    ->and('baz', new IsBoolean())
;

// you can also construct the rule with an optional key initially
$rule2 = IsDefinedArray::ofMaybe('bar', new IsInteger())
    ->and('foo', new IsString())
    ->and('baz', new IsBoolean())
    ->withNoOtherKeys() // you can also disallow unknown keys
;
```

## Combination

### Compose

This will combine other Rules in sequence to combine a new Rule from the original input type to the final output type.

For example, the composition of `A -> B`, `B -> C`, and `C -> D` would be `A -> D`.

This Rule will short-circuit if one of the composed Rules fails.
#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Callback;
use LeightonThomas\Validation\Rule\Combination\Compose;
use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
use LeightonThomas\Validation\ValidationResult;

$mixedToInteger = new IsInteger();
/** @psalm-var Callback<int, positive-int> $integerToPositiveInteger */
$integerToPositiveInteger = new Callback(
    function(int $value, ValidationResult $result) {
        if ($value > 0) {
            return;
        }
        
        $result->addError('This value is not positive.');
    }
);

// this is Rule<mixed, positive-int>
$composed = Compose::from($mixedToInteger)->and($integerToPositiveInteger);
```

### Intersection

This will validate that the value satisfies all configured Rules and reflects Psalm's 
[intersection types](https://psalm.dev/docs/annotating_code/type_syntax/intersection_types/).

As an example, the intersection of `A -> B`, `A -> C`, and `A -> D` would be `A -> B&C&D`.

The input will be validated against every Rule by default and list all errors, however if a custom error message is 
provided then it will instead add a single error message and short-circuit. 

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Combination\Intersection;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;

interface Foo {}

interface Bar {}

// this is Rule<object, Foo&Bar>
$intersection = Intersection::of(new IsInstanceOf(Foo::class))->and(new IsInstanceOf(Bar::class));
```

### Union

This will validate that the value satisfies at least one of the configured Rules and reflects Psalm's 
[union types](https://psalm.dev/docs/annotating_code/type_syntax/union_types/).

As an example, the union of `A -> B`, `A -> C`, and `A -> D` would be `A -> B|C|D`.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Combination\Union;
use LeightonThomas\Validation\Rule\Object\IsInstanceOf;

interface Foo {}

interface Bar {}

// this is Rule<object, Foo|Bar>
$intersection = Union::of(new IsInstanceOf(Foo::class))->or(new IsInstanceOf(Bar::class));
```

## Object

### IsInstanceOf

This will validate that the input `object` is an instance of the configured class-string.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Object\IsInstanceOf;

interface Foo {}

// this is Rule<object, Foo>
$rule = new IsInstanceOf(Foo::class);
```

## Scalar/Boolean

### IsBoolean

This will validate that the input is of type `bool`.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;

$rule = new IsBoolean();
```

## Scalar/Float

### IsFloat

This will validate that the input is of type `float`.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Float\IsFloat;

$rule = new IsFloat();
```

## Scalar/Integer

### IsInteger

This will validate that the input is of type `int`.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;

$rule = new IsInteger();
```

## Scalar/Numeric

### IsGreaterThan

This will validate that the input is greater than (or equal to, if configured) a specific numeric value.

Numeric strings, integers, and floats are supported.

Due to limitations the input type must be the same type as the configured value as far as Psalm is concerned, but 
the code itself does not care.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Numeric\IsGreaterThan;

$rule1 = new IsGreaterThan('4');
$rule2 = new IsGreaterThan('4.2');
$rule3 = new IsGreaterThan(4);
$rule4 = new IsGreaterThan(4.2);

// allow the input to be equal to the configured value
$rule4->allowEqual();
```

### IsLessThan

This will validate that the input is less than (or equal to, if configured) a specific numeric value.

Numeric strings, integers, and floats are supported.

Due to limitations the input type must be the same type as the configured value as far as Psalm is concerned, 
but the code itself does not care.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Numeric\IsLessThan;

$rule1 = new IsLessThan('4');
$rule2 = new IsLessThan('4.2');
$rule3 = new IsLessThan(4);
$rule4 = new IsLessThan(4.2);

// allow the input to be equal to the configured value
$rule4->allowEqual();
```

## Scalar/Strings

### IsString

This will validate that the input is of type `string`.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;

$rule = new IsString();
```

### Length

This will validate that the input's length is between the configured values.

Both minimum and maximum are inclusive.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Strings\Length;

// this is Rule<string, string>
$ruleBoth = new Length(4, 5);
$ruleOnlyMin = new Length(4);
$ruleOnlyMax = new Length(null, 5);
```

### Regex

This will validate that the input matches (or does not match) the configured regular expression.

#### Example

```php
<?php

use LeightonThomas\Validation\Rule\Scalar\Strings\Regex;

// this is Rule<string, string>
$rule = new Regex('/^hello_\d$/');

// you can also configure it to NOT match
$rule->doesNotMatch();
```
