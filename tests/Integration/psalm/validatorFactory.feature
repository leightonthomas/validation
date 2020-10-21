Feature: ValidatorFactory with no plugin
  In order to write and understand code correctly
  As a user of the library
  I need to be able to see the correct type from the ValidatorFactory's methods

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

      use LeightonThomas\Validation\ValidatorFactory;
      use LeightonThomas\Validation\Rule\Scalar\Strings\IsString;
      use LeightonThomas\Validation\Rule\Scalar\Strings\Length;
      use LeightonThomas\Validation\Rule\Scalar\Integer\IsInteger;
      use LeightonThomas\Validation\Rule\Anything;

      $factory = new ValidatorFactory();
      """

  Scenario Outline: Create will return the correct type of validator
    Given I have the following code
      """
      $validator = $factory->create(<rule>);

      /** @psalm-trace $validator */
      """
    When I run Psalm
    Then I see these errors
      | Type  | Message   |
      | Trace | <message> |
    And I see no other errors

    Examples:
      | rule             | message                                                         |
      | new IsString()   | $validator: LeightonThomas\Validation\Validator<mixed, string>  |
      | new IsInteger()  | $validator: LeightonThomas\Validation\Validator<mixed, int>     |
      | new Anything()   | $validator: LeightonThomas\Validation\Validator<mixed, mixed>   |
      | new Length(1, 2) | $validator: LeightonThomas\Validation\Validator<string, string> |
