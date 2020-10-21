Feature: IsDefinedArray Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see a type from the IsDefinedArray rule

  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
      <psalm totallyTyped="true" %s>
        <projectFiles>
          <directory name="."/>
        </projectFiles>
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
      """

  Scenario: It will return a generic array type on construction
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                              |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array<array-key, mixed>> |
    And I see no other errors

  Scenario: It will return a generic array type on construction of optional key
    Given I have the following code
      """
      $rule = IsDefinedArray::ofMaybe('a', new IsString());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                              |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array<array-key, mixed>> |
    And I see no other errors

  Scenario: It will return a generic array type on another key being added
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())->and(4, new IsInteger());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                              |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array<array-key, mixed>> |
    And I see no other errors

  Scenario: It will return a generic array type on an optional key being added
    Given I have the following code
      """
      $rule = IsDefinedArray::of('a', new IsString())->andMaybe(4, new IsInteger());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                              |
      | Trace | $rule: LeightonThomas\Validation\Rule\Arrays\IsDefinedArray<array<array-key, mixed>> |
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
