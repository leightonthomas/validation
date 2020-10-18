Feature: Union Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the Union rule

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

      namespace Tests\Validation;

      use Validation\Rule\Combination\Union;
      use Validation\Rule\Arrays\IsArray;
      use Validation\Rule\Scalar\Strings\IsString;
      use Validation\Rule\Scalar\Integer\IsInteger;
      """

  Scenario: It will return the correct type on construction
    Given I have the following code
      """
      $rule = Union::of(new IsString());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                 |
      | Trace | $rule: Validation\Rule\Combination\Union<mixed, string> |
    And I see no other errors

  Scenario: It will return the correct type on addition of new keys
    Given I have the following code
      """
      $rule = Union::of(new IsString())
          ->or(new IsInteger())
          ->or(new IsArray())
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                               |
      | Trace | $rule: Validation\Rule\Combination\Union<mixed, array<array-key, mixed>\|int\|string> |
    And I see no other errors

  Scenario: It will return the correct type if nested
    Given I have the following code
      """
      $rule1 = Union::of(new IsString())
          ->or(new IsInteger())
      ;

      $rule2 = Union::of($rule1)
          ->or(new IsArray())
      ;

      /** @psalm-trace $rule2 */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                |
      | Trace | $rule2: Validation\Rule\Combination\Union<mixed, array<array-key, mixed>\|int\|string> |
    And I see no other errors
