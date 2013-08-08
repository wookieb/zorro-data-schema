<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Assert\Assertion;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassOptions extends GlobalClassOptions
{
    private $className;
    private $propertyMap = array();

    public function setClassName($className)
    {
        Assertion::nullOrnotBlank($className, 'Class name cannot be empty');
        $this->className = $className;
        return $this;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function mapProperty($sourceProperty, $targetProperty)
    {
        $this->propertyMap[$sourceProperty] = $targetProperty;
        return $this;
    }

    public function getMappedProperties()
    {
        return $this->propertyMap;
    }
}