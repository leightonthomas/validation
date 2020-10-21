Feature: IsLessThan Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the IsLessThan rule

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

      use LeightonThomas\Validation\Rule\Scalar\Numeric\IsLessThan;
      """

  Scenario Outline: It will return the correct type on construction
    Given I have the following code
      """
      $rule = new IsLessThan(<value>);

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                 |
      | Trace | $rule: LeightonThomas\Validation\Rule\Scalar\Numeric\IsLessThan<<type>> |
    And I see no other errors

    Examples:
      | value | type        |
      | 4     | int(4)      |
      | 4.1   | float(4.1)  |
      | '4'   | string(4)   |
      | '4.1' | string(4.1) |

  Scenario Outline: It will add Psalm issue if non-numeric value given
    Given I have the following code
      """
      new IsLessThan(<value>);
      """
    When I run Psalm
    Then I see these errors
      | Type    | Message                                                                                                              |
      | <error> | Argument 1 of LeightonThomas\Validation\Rule\Scalar\Numeric\IsLessThan::__construct expects numeric, <type> provided |
    And I see no other errors

    Examples:
      | value           | error                 | type                  |
      | 'hello'         | InvalidScalarArgument | string(hello)         |
      | []              | InvalidArgument       | array<empty, empty>   |
      | new \stdClass() | InvalidArgument       | stdClass              |
      | true            | InvalidScalarArgument | true                  |
      | fn() => 4       | InvalidArgument       | pure-Closure():int(4) |
