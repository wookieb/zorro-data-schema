<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders;
use Wookieb\ZorroDataSchema\Schema\Builder\SchemaBuilder;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
interface SchemaBuilderAwareInterface
{
    function setSchemaBuilder(SchemaBuilder $builder);
}