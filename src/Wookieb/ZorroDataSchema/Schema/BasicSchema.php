<?php

namespace Wookieb\ZorroDataSchema\Schema;
use Wookieb\ZorroDataSchema\Schema\DynamicType\DynamicCollectionType;
use Wookieb\ZorroDataSchema\Type\BooleanType;
use Wookieb\ZorroDataSchema\Type\DateType;
use Wookieb\ZorroDataSchema\Type\FloatType;
use Wookieb\ZorroDataSchema\Type\IntegerType;
use Wookieb\ZorroDataSchema\Type\StringType;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class BasicSchema extends Schema
{
    public function __construct()
    {
        $this->registerType(new StringType());
        $this->registerType(new FloatType());
        $this->registerType(new IntegerType());
        $this->registerType(new BooleanType());
        $this->registerType(new DateType());

        $this->registerDynamicType(new DynamicCollectionType($this));
    }
}