Feature: Validator with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the Validator's methods

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

      use Validation\Validator;
      use Validation\ValidationResult;
      use Validation\Rule\Rule;
      use Validation\Rule\Scalar\Strings\IsString;
      use Validation\Rule\Scalar\Integer\IsInteger;
      use Validation\Rule\Scalar\Strings\Length;
      use Validation\Rule\Anything;
      use Validation\Checker\Checker;

      """

  Scenario Outline: It will have the correct type on construction
    Given I have the following code
      """
      /** @psalm-var Checker<Rule<ruleType>> $checker */
      $checker = new class implements Checker {
        public function check($value, Rule $rule, ValidationResult $result): void {}

        public function canCheck(): array
        {
          return [];
        }
      };

      $validator = new Validator(<rule>, [$checker]);

      /** @psalm-trace $validator */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message                                    |
      | Trace | $validator: Validation\Validator<ruleType> |
    And I see no other errors

    Examples:
      | rule             | ruleType         |
      | new IsString()   | <mixed, string>  |
      | new IsInteger()  | <mixed, int>     |
      | new Anything()   | <mixed, mixed>   |
      | new Length(1, 2) | <string, string> |

  Scenario: It will add a Psalm error if input does not match I template var type
    Given I have the following code
      """
      /** @psalm-var Checker<IsString> $checker */
      $checker = new class implements Checker {
        public function check($value, Rule $rule, ValidationResult $result): void {}

        public function canCheck(): array
        {
          return [];
        }
      };

      $validator = new Validator(new Length(7), [$checker]);
      $validator->validate(4);
      """
    When I run Psalm
    Then I see these errors
      | Type                  | Message                                                                      |
      | InvalidScalarArgument | Argument 1 of Validation\Validator::validate expects string, int(4) provided |
    And I see no other errors
