<?php

declare(strict_types=1);

namespace LeightonThomas\Validation\Plugin;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

use function class_exists;

/**
 * @internal
 */
class Plugin implements PluginEntryPointInterface
{

    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null)
    {
        // This seems to be required to auto-loaded the class, based on what's in an official Psalm plugin
        class_exists(Rule\Arrays\IsDefinedArrayReturnTypeProvider::class, true);
        $psalm->registerHooksFromClass(Rule\Arrays\IsDefinedArrayReturnTypeProvider::class);
    }
}
