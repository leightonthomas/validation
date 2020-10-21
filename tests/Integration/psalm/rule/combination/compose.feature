Feature: Compose Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the Compose rule

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

      use LeightonThomas\Validation\Rule\Rule;
      use LeightonThomas\Validation\Rule\Combination\Compose;
      use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
      use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
      use LeightonThomas\Validation\Rule\Arrays\IsArray;
      """

  Scenario: It will return the correct type on construction
    Given I have the following code
      """
      $rule = Compose::from(new IsString());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                  |
      | Trace | $rule: LeightonThomas\Validation\Rule\Combination\Compose<mixed, string> |
    And I see no other errors

  Scenario: It will return the correct type on addition of rules
    Given I have the following code
      """
      $rule = Compose::from(new IsString())
          ->and(new IsInteger())
          ->and(new IsArray())
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                   |
      | Trace | $rule: LeightonThomas\Validation\Rule\Combination\Compose<mixed, array<array-key, mixed>> |
    And I see no other errors

  Scenario: It will return the correct type if nested
    Given I have the following code
      """
      $rule1 = Compose::from(new IsString())
          ->and(new IsInteger())
      ;

      $rule2 = Compose::from($rule1)
          ->and(new IsArray())
      ;

      /** @psalm-trace $rule2 */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                    |
      | Trace | $rule2: LeightonThomas\Validation\Rule\Combination\Compose<mixed, array<array-key, mixed>> |
    And I see no other errors

  Scenario: It will add Psalm issue if input type of new rule doesn't match output type of previous
    Given I have the following code
      """
      /**
       * @extends Rule<int, positive-int>
       */
      class SomeOtherRule extends Rule {
          public function __construct()
          {
              $this->messages = [];
          }
      }

      Compose::from(new IsString())
          ->and(new SomeOtherRule())
      ;
      """
    When I run Psalm
    Then I see these errors
      | Type                  | Message                                                                                                                                                                                  |
      | InvalidScalarArgument | Argument 1 of LeightonThomas\Validation\Rule\Combination\Compose::and expects LeightonThomas\Validation\Rule\Rule<string, mixed>, Tests\LeightonThomas\Validation\SomeOtherRule provided |
    And I see no other errors
