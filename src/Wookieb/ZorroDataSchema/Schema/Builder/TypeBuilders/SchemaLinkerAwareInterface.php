<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaLinker;

/**
 * Interface for those builders who need current schema linker
 *
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaLinkerAwareInterface
{
    function setSchemaLinker(SchemaLinker $linker);
}
