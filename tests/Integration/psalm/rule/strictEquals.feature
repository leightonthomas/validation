Feature: StrictEquals Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the StrictEquals rule

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
      """

  Scenario Outline: It will return the correct type on construction
    Given I have the following code
      """
      $rule = new StrictEquals(<input>);

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                    |
      | Trace | $rule: LeightonThomas\Validation\Rule\StrictEquals<<type>> |
    And I see no other errors

    Examples:
      | input         | type                |
      | 4             | int(4)              |
      | 4.3           | float(4.3)          |
      | 'a'           | string(a)           |
      | true          | true                |
      | false         | false               |
      | null          | null                |
      | new \stdClass | stdClass            |
      | []            | array<empty, empty> |
      | ['a']         | array{string(a)}    |
