Feature: IsDefinedArray Rule with the plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct return types for the IsDefinedArray rule
  By using the plugin

  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
      <psalm totallyTyped="true" %s>
        <projectFiles>
          <directory name="."/>
        </projectFiles>
        <plugins>
          <pluginClass class="LeightonThomas\Validation\Plugin\Plugin"/>
        </plugins>
      </psalm>
      """
    And I have the following code preamble
      """
      <?php

      declare(strict_types=1);

      namespace Tests\LeightonThomas\Validation;

      use LeightonThomas\Validation\Rule\Arrays\IsDefinedArray;
      use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
      use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
      use LeightonThomas\Validation\Rule\Scalar\Boolean\IsBoolean;
      use LeightonThomas\Validation\Rule\Combination\Union;
      """

  Scenario: It will return the correct type on construction
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                       |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{a: string}> |
    And I see no other errors

  Scenario: It will return the correct type on construction of optional key
    Given I have the following code
      """
      $rule = IsDefinedArray::ofMaybe('a', new IsString());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                        |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{a?: string}> |
    And I see no other errors

  Scenario: It will return the correct type when more keys are added
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())
          ->and(4, new IsInteger())
          ->and('another', Union::of(new IsString())->or(new IsInteger()))
          ->andMaybe('someKey', new IsBoolean())
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                                                     |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{4: int, a: string, another: int\|string, someKey?: bool}> |
    And I see no other errors

  Scenario: It will return the correct type even if nested
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())
          ->and(
              4,
              IsDefinedArray::of(
                  'b',
                  IsDefinedArray::ofMaybe(
                      'c',
                      new IsString()
                  )
              )
          )
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                                       |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{4: array{b: array{c?: string}}, a: string}> |
    And I see no other errors

  Scenario: It will return the correct type when duplicate keys provided
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())
          ->and('a', new IsInteger())
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                    |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{a: int}> |
    And I see no other errors

  Scenario: It will return the correct type when duplicate keys provided but newer one is optional
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())
          ->andMaybe('a', new IsInteger())
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                     |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{a?: int}> |
    And I see no other errors

  Scenario: It will retain the correct type when disallowing additional keys
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())
          ->withNoOtherKeys()
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                       |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{a: string}> |
    And I see no other errors

  Scenario: It will return a type of array if invalid data is given
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())
          ->and(
              4,
              IsDefinedArray::of(
                  'b',
                  IsDefinedArray::of(
                      'c',
                      'not a rule'
                  )
              )
          )
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type            | Message                                                                                                                                                       |
      | InvalidArgument | Argument 2 of LeightonThomas\Validation\Rule\Arrays\IsDefinedArray::of expects LeightonThomas\Validation\Rule\Rule<mixed, mixed>, string(not a rule) provided |
      | Trace           | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array{4: array{b: array<array-key, mixed>}, a: string}>                                           |
    And I see no other errors

  Scenario: It will add a Psalm issue if key is not array-key
    Given I have the following code
      """
      $rule = IsDefinedArray::of([], new IsString())->and(4, new IsInteger());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type            | Message                                                                                                                |
      | InvalidArgument | Argument 1 of LeightonThomas\Validation\Rule\Arrays\IsDefinedArray::of expects array-key, array<empty, empty> provided |
