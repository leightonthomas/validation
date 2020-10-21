Feature: Rules with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from Rules

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

      use LeightonThomas\Validation\Rule\StrictEquals;
      use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
      use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
      use LeightonThomas\Validation\Rule\Combination\Union;
      """

  Scenario Outline: It will return the correct type when setMessage is called on a Rule
    Given I have the following code
      """
      $rule = <rule>->setMessage(0, 'some message doesnt matter');

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message       |
      | Trace | $rule: <type> |
    And I see no other errors

    Examples:
      | rule                                                        | type                                                                          |
      | (new IsString())                                            | LeightonThomas\Validation\Rule\Scalar\Strings\IsString                        |
      | (new IsInteger())                                           | LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger                       |
      | Union::of(new StrictEquals('a'))->or(new StrictEquals('b')) | LeightonThomas\Validation\Rule\Combination\Union<mixed, string(a)\|string(b)> |
