Feature: Intersection Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the Intersection rule

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

      use Validation\Rule\Combination\Intersection;
      use Validation\Rule\Object\IsInstanceOf;
      use Validation\Rule\Scalar\Strings\IsString;

      interface Foo {}

      interface Bar {}

      interface Baz {}
      """

  Scenario: It will return the correct type on construction
    Given I have the following code
      """
      $rule = Intersection::of(new IsInstanceOf(Foo::class));

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                      |
      | Trace | $rule: Validation\Rule\Combination\Intersection<mixed, Tests\Validation\Foo> |
    And I see no other errors

  Scenario: It will return the correct type on addition of new keys
    Given I have the following code
      """
      $rule = Intersection::of(new IsInstanceOf(Foo::class))
          ->and(new IsInstanceOf(Bar::class))
      ;

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                           |
      | Trace | $rule: Validation\Rule\Combination\Intersection<mixed, Tests\Validation\Foo&Tests\Validation\Bar> |
    And I see no other errors

  Scenario: It will return the correct type if nested
    Given I have the following code
      """
      $rule1 = Intersection::of(new IsInstanceOf(Foo::class))
          ->and(new IsInstanceOf(Bar::class))
      ;

      $rule2 = Intersection::of($rule1)
          ->and(new IsInstanceOf(Baz::class))
      ;

      /** @psalm-trace $rule2 */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                                                                                 |
      | Trace | $rule2: Validation\Rule\Combination\Intersection<mixed, Tests\Validation\Foo&Tests\Validation\Baz&Tests\Validation\Bar> |
    And I see no other errors

  Scenario: It will return errors if non-object type used for construction
    Given I have the following code
      """
      $rule = Intersection::of(new IsString());
      """
    When I run Psalm
    Then I see these errors
      | Type            | Message                                                                                                                                                  |
      | InvalidArgument | Argument 1 of Validation\Rule\Combination\Intersection::of expects Validation\Rule\Rule<mixed, object>, Validation\Rule\Scalar\Strings\IsString provided |
    And I see no other errors

  Scenario: It will return errors if non-object type used as additional rule
    Given I have the following code
      """
      $rule = Intersection::of(new IsInstanceOf(Foo::class))
          ->and(new IsString())
      ;
      """
    When I run Psalm
    Then I see these errors
      | Type            | Message                                                                                                                                                   |
      | InvalidArgument | Argument 1 of Validation\Rule\Combination\Intersection::and expects Validation\Rule\Rule<mixed, object>, Validation\Rule\Scalar\Strings\IsString provided |
    And I see no other errors
