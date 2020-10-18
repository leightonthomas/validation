<?php

namespace Tests\Validation\Integration;

use Codeception\Actor;
use Codeception\Lib\Friend;
use Tests\Validation\Integration\_generated\PsalmTesterActions;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method Friend haveFriend($name, $actorClass = NULL)
 */
class PsalmTester extends Actor
{
    use PsalmTesterActions;
}
