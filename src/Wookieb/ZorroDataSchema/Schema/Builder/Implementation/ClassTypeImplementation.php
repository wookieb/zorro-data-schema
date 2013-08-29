<?php

namespace Wookieb\ZorroDataSchema\Schema\Builder\Implementation;
use Assert\Assertion;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ClassTypeImplementation extends GlobalClassTypeImplementation
{
    private $name;
    private $className;
    private $propertiesImplementation = array();

    public function __construct($name)
    {
        Assertion::notBlank($name, 'Class type implementation name cannot be empty');
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

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

    public function addPropertyImplementation(PropertyImplementation $propertyImplementation)
    {
        $this->propertiesImplementation[$propertyImplementation->getName()] = $propertyImplementation;
        return $this;
    }

    public function getImplementationForProperty($propertyName)
    {
        if (isset($this->propertiesImplementation[$propertyName])) {
            return $this->propertiesImplementation[$propertyName];
        }

        $setterName = null;
        $getterName = null;
        if ($this->accessorsEnabled) {
            if (!$this->style) {
                throw new \BadMethodCallException('Cannot generate setters nad getters name without naming style');
            }
            $setterName = $this->style->generateSetterName($propertyName);
            $getterName = $this->style->generateGetterName($propertyName);
        }

        $propertyImplementation = new PropertyImplementation($propertyName);
        $propertyImplementation->setTargetPropertyName($propertyName);
        if ($setterName) {
            $propertyImplementation->setSetter($setterName);
        }
        if ($getterName) {
            $propertyImplementation->setGetter($getterName);
        }
        return $propertyImplementation;
    }
}
