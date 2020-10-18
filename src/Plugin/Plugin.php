<?php

declare(strict_types=1);

namespace Validation\Plugin;

use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

use function class_exists;

/**
 * @internal
 */
class Plugin implements PluginEntryPointInterface
{

    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null)
    {
        // This is plugin entry point. You can initialize things you need here,
        // and hook them into psalm using RegistrationInterface
        //
        // Here's some examples:
        // 1. Add a stub file
        // ```php
        // $psalm->addStubFile(__DIR__ . '/stubs/YourStub.php');
        // ```

        // This seems to be required to auto-loaded the class, based on what's in an official Psalm plugin
        class_exists(Rule\Arrays\IsDefinedArrayReturnTypeProvider::class, true);
        $psalm->registerHooksFromClass(Rule\Arrays\IsDefinedArrayReturnTypeProvider::class);

        // Psalm allows arbitrary content to be stored under you plugin entry in
        // its config file, psalm.xml, so you plugin users can put some configuration
        // values there. They will be provided to your plugin entry point in $config
        // parameter, as a SimpleXmlElement object. If there's no configuration present,
        // null will be passed instead.
    }
}
