<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\BasicTypesBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\ClassTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\CollectionTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\EnumTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\IntegerTypeBuilder;
use Wookieb\ZorroDataSchema\Schema\Builder\TypeBuilders\ChoiceTypeBuilder;

/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicSchemaLinker extends SchemaLinker
{
    public function __construct()
    {
        $this->registerTypeBuilder(new ClassTypeBuilder());
        $this->registerTypeBuilder(new CollectionTypeBuilder());
        $this->registerTypeBuilder(new EnumTypeBuilder());
        $this->registerTypeBuilder(new IntegerTypeBuilder());
        $this->registerTypeBuilder(new BasicTypesBuilder());
        $this->registerTypeBuilder(new ChoiceTypeBuilder());
    }
}
