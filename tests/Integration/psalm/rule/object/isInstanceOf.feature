Feature: IsInstanceOf Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the IsInstanceOf rule

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

      use LeightonThomas\Validation\Rule\Object\IsInstanceOf;

      interface Foo {}

      interface Bar {}

      interface Baz {}
      """

  Scenario: It will return the correct type on construction
    Given I have the following code
      """
      $rule = new IsInstanceOf(Foo::class);

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                               |
      | Trace | $rule: LeightonThomas\Validation\Rule\Object\IsInstanceOf<mixed, Tests\LeightonThomas\Validation\Foo> |
    And I see no other errors

  Scenario: It will return errors if non-class string used for construction
    Given I have the following code
      """
      $rule = new IsInstanceOf('abc');
      """
    When I run Psalm
    Then I see these errors
      | Type                 | Message                                                                                                                              |
      | ArgumentTypeCoercion | Argument 1 of LeightonThomas\Validation\Rule\Object\IsInstanceOf::__construct expects class-string, parent type string(abc) provided |
      | UndefinedClass       | Class or interface abc does not exist                                                                                                |
    And I see no other errors
