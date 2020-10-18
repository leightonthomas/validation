Feature: IsArray Rule with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see a type from the IsArray rule

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

      use Validation\Rule\Anything;
      use Validation\Rule\Arrays\IsArray;
      use Validation\Rule\Scalar\Strings\IsString;
      use Validation\Rule\Scalar\Integer\IsInteger;
      """

  Scenario: It will cause Psalm issues if a non-array-key Rule is used for the key rule
    Given I have the following code
      """
      $rule = new IsArray(new IsArray());

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type            | Message                                                                                                                                                                   |
      | InvalidArgument | Argument 1 of Validation\Rule\Arrays\IsArray::__construct expects Validation\Rule\Rule<mixed, array-key>\|null, Validation\Rule\Arrays\IsArray<array-key, mixed> provided |

  Scenario: It will cause no Psalm issues if valid with no custom keys and have the correct type
    Given I have the following code
      """
      $rule = new IsArray();

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                                 |
      | Trace | $rule: Validation\Rule\Arrays\IsArray<array-key, mixed> |

  Scenario Outline: It will cause no Psalm issues if valid with custom keys and have the correct type
    Given I have the following code
      """
      $rule = new IsArray(<keyRule>, <valueRule>);

      /** @psalm-trace $rule */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                              |
      | Trace | $rule: Validation\Rule\Arrays\IsArray<expectedTypes> |

    Examples:
      | keyRule        | valueRule      | expectedTypes                        |
      | new IsString() | null           | <string, mixed>                      |
      | null           | new IsString() | <array-key, string>                  |
      | null           | new IsArray()  | <array-key, array<array-key, mixed>> |
      | new IsInteger  | new IsString() | <int, string>                        |
